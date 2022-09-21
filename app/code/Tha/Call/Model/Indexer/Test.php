<?php
namespace Tha\Call\Model\Indexer;

use Jmango360\Japi\Model\Catalog\Listing;
use Jmango360\Japi\Model\Product;
use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\StoreManagerInterface;
class Test implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{

	protected $storeManagerInterface;
	protected $resourceConnection;
	protected $logger;
    protected $jm_product;
    protected $jm_catelog_listing;
    protected $_collection;
    protected $productCollectionFactory;
    protected $storeManager;
    protected $catalogProductVisibility;
    protected $japiProductHelper;
    protected $catalogConfig;

	public function __construct(
		StoreManagerInterface $storeManagerInterface,
		ResourceConnection $resourceConnection,
		\Psr\Log\LoggerInterface $logger,
        Product $jm_product,
        Listing $jm_catelog_listing,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility                      $catalogProductVisibility,
        \Jmango360\Japi\Helper\Product                                 $japiProductHelper,
        \Magento\Catalog\Model\Config                                  $catalogConfig
	)
	{
		$this->storeManagerInterface = $storeManagerInterface;
		$this->resourceConnection = $resourceConnection;	
		$this->logger = $logger;
        $this->jm_product = $jm_product;
        $this->jm_catelog_listing = $jm_catelog_listing;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->japiProductHelper = $japiProductHelper;
        $this->catalogConfig = $catalogConfig;
	}

	/*
	 * Used by mview, allows process indexer in the "Update on schedule" mode
	 */
	public function execute($ids){
		//code here!
	}

	/*
	 * Will take all of the data and reindex
	 * Will run when reindex via command line
	 * sẽ chạy function này khi đánh index: php bin/magento indexer:reindex tha_call_indexer
	 */
	public function executeFull(){
		//code here!
        // $size = $this->runs();
	}
    
    public function runs()
    {
        $stores = $this->storeManagerInterface;
        $all_stores = $stores->getStores();
        foreach (array_keys($all_stores) as $store) {
            $stores->setCurrentStore($store);
            $this->storeManager = $stores;

            // $root_category = $stores->getStore()->getRootCategoryId();
            // $jm_product = $this->jm_product;
            // $jm_catelog_listing = $this->jm_catelog_listing;
            // $jm_catelog_listing->init(false);
            // $toolbar = $jm_catelog_listing->getToolbarInfo();
            // $all_pro_datas = $jm_product->getListV3();

            $this->_collection = $this->_getCollection(false);

            /**
             * Apply Hide-on-App filter
             */
            $this->japiProductHelper->addProductTypeFilter($this->_collection);

            /**
             * Apply product type filter
             */
            $this->japiProductHelper->addHideOnAppFilter($this->_collection);
            $limit = 10;
            $pages = ceil($this->_collection->getSize()/$limit);

            for ($i=1; $i <= $pages; $i++) { 
                $this->_collection->setPage($i, $limit);
                $products = $this->getProducts();
                $products_value = array_map(function($product){
                                        $product_datas = $product->getData();
                                        foreach ($product_datas as $key => $values) {

                                            // if (is_array($values) && !empty($values)) {
                                            //     if (is_object($values[0])) {
                                            //         $product_datas[$key] = array_map(function($value)
                                            //         {
                                            //             return $value->getData();
                                            //         }, $values);
                                            //     }
                                            // }
                                        }
                                        // return $product->getData();
                                        return $product_datas;
                                    },$products);
                $data = ['store_id' => $store, "products" => $products];
                $data_encode = $this->serialize->serialize($data);
                // $this->_send("POST", "http://localhost/laravel1/pub/magento", json_encode($data));
            }
        }
    }

    public function convert_data($data)
    {
        if (is_object($data)) {
            $data_values = $data->getData();
            foreach ($data_values as $key => $value) {
                if (is_object($data)) {
                    $data_values[$key] = $this->convert_data($value);
                }
            }
        }elseif (is_array($data) && !empty($data)) {
            return array_map(function($_data){return $this->convert_data($_data);}, $data);
        }
        return $data;
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
            $collection
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents();
        }

        return $collection
            //->addMinimalPrice()
            //->addFinalPrice()
            //->addTaxPercents()
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
        $this->log(strtoupper($method), [$url, $data]);

        //execute
        $response = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //get error if occurred
        $error = curl_error($ch);
        //free resources
        curl_close($ch);

        //log
        $this->log('Response code', [$responseCode, $response]);

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
     * Log to file
     *
     * @param $message
     */
    protected function log($message)
    {
        if ($this->logger) {
            $this->logger->info($message);
        }
    }  
   
	/*
	 * Works with a set of entity changed (may be massaction)
	 */
	public function executeList(array $ids){
		//code here!
	}
   
	/*
	 * Works in runtime for a single entity using plugins
	 */
	public function executeRow($id){
		//code here!
	}
}