<?php

namespace Tha\Nan\Controller\Dev;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Indexer\ConfigInterface;
use Magento\Framework\View\Result\PageFactory;

class reindex extends Action implements ActionInterface
{
    protected $pageFactory;
    protected $indexerFactory;

    /**
     * @var \Magento\Framework\Indexer\ConfigInterface
     */
    protected $config;

    public function __construct(
        Context $context,
        \Magento\Indexer\Model\IndexerFactory $indexerFactory,
        ConfigInterface $configInterface,
        PageFactory $pageFactory
    )
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->indexerFactory = $indexerFactory;
        $this->config = $configInterface;
    }
        
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        if(isset($params['run'])){
            if($params['run'] == 'all'){
                $this->reindexAll(); 
                echo "reindex all";
            }else{
                $this->reindexOne($params['run']);
                echo "reindex with ".$params['run'];
            }   
        }else {
            $this->reindexAll();
            echo "reindex all by auto";
        }   
        exit();
    }

    /**
     * Regenerate single index
     *
     * @return void
     * @throws \Exception
     */
    private function reindexOne($indexId){
        $indexer = $this->indexerFactory->create()->load($indexId);
        $indexer->reindexAll();
    }

    /**
     * Regenerate all index
     *
     * @return void
     * @throws \Exception
     */
    private function reindexAll(){
        foreach (array_keys($this->config->getIndexers()) as $indexerId) {          
            $indexer = $this->indexerFactory->create()->load($indexerId);
            $indexer->reindexAll();            
        }
    }
}

?>