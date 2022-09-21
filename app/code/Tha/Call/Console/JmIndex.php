<?php
namespace Tha\Call\Console;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\Area;

class JmIndex extends Command
{
    const STORE_ID = "store_id";
    protected $_collection;
	protected $storeManagerInterface;
    protected $japiProductHelper;
    protected $productCollectionFactory;
    protected $catalogProductVisibility;
    protected $catalogConfig;
    protected $storeManager;
    protected $state;
    protected $serviceOutputProcessor;
    protected $listV3Factory;

	public function __construct(
        \Magento\Framework\App\State                                   $state,
        \Magento\Catalog\Model\Config                                  $catalogConfig,
        \Jmango360\Japi\Model\Data\Product\ListV3Factory               $listV3Factory,
        \Jmango360\Japi\Helper\Product                                 $japiProductHelper,
		\Magento\Store\Model\StoreManagerInterface                     $storeManagerInterface,
        \Jmango360\Japi\Model\Rest\ServiceOutputProcessor              $serviceOutputProcessor,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility                      $catalogProductVisibility,
        \Magento\LayeredNavigation\Block\Navigation $navigation
	)
	{
        $this->state = $state;
        $this->catalogConfig = $catalogConfig;
        $this->listV3Factory = $listV3Factory;
        $this->japiProductHelper = $japiProductHelper;
		$this->storeManagerInterface = $storeManagerInterface;
        $this->serviceOutputProcessor = $serviceOutputProcessor;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
       
        parent::__construct();
	}

    protected function configure()
    {
         $options = [
             new InputOption(
                 self::STORE_ID,
                 null,
                 InputOption::VALUE_REQUIRED,
                 'store_id'
             )
         ];
 
        $this->setName('jm360:reindex')
             ->setDescription('jm360 reindex product')
             ->setDefinition($options);
        
        parent::configure();
    }

	/*
	 * Used by mview, allows process indexer in the "Update on schedule" mode
	 */
	public function execute(InputInterface $input, OutputInterface $output){
		//code here!
        $current_stores = null;
        $store_ids = $input->getOption(self::STORE_ID);
        if ($store_ids == "all") {
            # code...
        }else {
            $current_stores = explode(",", $store_ids);
        }
        
        $this->runs($current_stores);
	}

    /**
     * run for reindex product
     */
    public function runs($current_stores)
    {
        # code...
        $enpoin_url = ""; //"http://zxc.com/laravel1/public/magento";
        if (!$enpoin_url) {
            echo("not has enpoin to get data");
            return;
        }
        $storeManagerInterface = $this->storeManagerInterface;
        $all_stores = $storeManagerInterface->getStores();
        $current_stores = $current_stores ?? array_keys($all_stores);
        // tạo môi trường frontend cho cli(error: "Area code is not set" from function: getAreaCode of: vendor\magento\framework\App\State.php)
        $this->state->setAreaCode(Area::AREA_FRONTEND);
        // write log for process
        foreach ($current_stores as $store) {
            $this->logdata("begin with jmango360_reindex process with store_id=$store");
            // $storeManagerInterface = $this->storeManagerInterface;
            $storeManagerInterface->setCurrentStore((int) $store);
            $this->storeManager = $storeManagerInterface;
            $this->init(false);
            $limit = 10; $size = $this->_collection->getSize(); $this->logdata("all collections=$size");
            $page_nums = ceil($size/$limit);
            $page_done = "page-done=";
            for ($i=1; $i <= $page_nums; $i++) { 
                try {
                    # code...
                    $this->_collection->setPage($i, $limit);
                    $all_pro = $this->getProducts(); // Jmango360\Japi\Model\Data\Product\ProductDetailsV3
                    $data_convert = $this->convert_pro_to_listV3($all_pro);
                    $outputData = $this->serviceOutputProcessor->process(
                        $data_convert,
                        "Jmango360\Japi\Api\ProductInterface",
                        "getListV3"
                    );
                    // send data to server
                    $this->logdata("begin send to $enpoin_url");
                    $this->_send("POST", $enpoin_url, json_encode(["store_id" => $store, "data" => $outputData]));
                    $page_done.="$i&";
                } catch (Exception $e) {
                    //throw $th;
                    $message = $e->getMessage();
                    $file = $e->getFile();
                    $line = $e->getLine();
                    $this->logdata("error: $message ->"." file: $file -> "."line: $line");
                }
                $this->_collection->clear();
            }
            $this->logdata("end of jmango360_reindex process with store_id=$store");
            echo $page_done;
        }
    }

    /**
     * Make HTTP request using cURL extension
     *
     * @param string $method
     * @param string $url
     * @param mixed $data
     * @param mixed $headers
     * @return string
     * @throws \Exception
     */
    protected function _send($method, $url, $data, $headers = array())
    {
        if (!function_exists('curl_init')) {
            throw new \Exception('PHP cURL extension is missing.');
        }

        if (strtolower($method == 'get') && $data) {
            $url .= is_array($data) ? http_build_query($data) : $data;
        }

        //init and config request
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        if (strtolower($method) == 'post' && $data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        //log
        $this->logdata(strtoupper($method), [$url, $data]);

        //execute
        $response = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //get error if occurred
        $error = curl_error($ch);
        //free resources
        curl_close($ch);

        //log
        $this->logdata('Response code', [$responseCode, $response]);

        //return
        if ($responseCode == 200) {
            return $response;
        } else {
            if ($error) {
                throw new \Exception($error);
            } else {
                throw new \Exception($responseCode);
            }
        }
    }

    /**
     * @param $needPrice
     * @return void
     * @throws
     */
    public function init($needPrice = true)
    {
        $this->_collection = $this->_getCollection($needPrice);

        /**
         * Apply Hide-on-App filter
         */
        $this->japiProductHelper->addProductTypeFilter($this->_collection);

        /**
         * Apply product type filter
         */
        $this->japiProductHelper->addHideOnAppFilter($this->_collection);
    }

    /**
     * Get product collection
     *
     * @param $needPrice
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     * @throws
     */
    protected function _getCollection($needPrice = true)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->productCollectionFactory->create()
            ->setStoreId($this->storeManager->getStore()->getId())
            ->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $this->_addProductAttributesAndPrices($collection, $needPrice);

        return $collection;
    }

    /**
     * Add all attributes and apply pricing logic to products collection
     * to get correct values in different products lists.
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param $needPrice
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _addProductAttributesAndPrices(
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
                                                                $needPrice = true
    ) {
        if ($needPrice) {
            $collection->addMinimalPrice()->addFinalPrice()->addTaxPercents();
        }

        return $collection
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addUrlRewrite();
    }

    /**
     * Get all products
     *
     * @return \Jmango360\Japi\Api\Data\Product\ProductDetailsV3Interface[]
     */
    public function getProducts()
    {
        return $this->japiProductHelper->convertProductCollectionToResponseV3($this->_collection);
    }

    /**
     * convert productlist detailV3 to array.
     */
    public function convert_pro_to_listV3(array $products)
    {
        $result = $this->listV3Factory->create();
        $result->setProducts($products);
        return $result;
    }

    /**
     * write log for process
     */
    public function logdata($conten)
    {
        # code...
        $writers = new \Zend\Log\Writer\Stream(BP . '/var/log/jm360reindex.log');
        $loggers = new \Zend\Log\Logger();
        try {
            $loggers->addWriter($writers);
            $loggers->info($conten); 
        } catch (Exception $e) {
            //throw $th;
            echo ($e->getMessage()."\n");
            echo ($e->getFile().".\n");
            echo ($e->getLine().".\n");
        }
    }

}