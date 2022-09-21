<?php
namespace Tha\Call\Helper;
 
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Indexer\Model\IndexerFactory;
use Magento\Indexer\Model\Indexer\Collection;

class reindexall extends AbstractHelper
{
    private $indexFactory;
    private $indexCollection;
 
    public function __construct(
        Context $context,
        IndexerFactory $indexFactory,
        Collection $indexCollection
    ) 
    {
        parent::__construct($context);
        $this->indexCollection = $indexCollection;
        $this->indexFactory = $indexFactory;
    }

    public function manuallIndexing()
    {
        $indexes = $this->indexCollection->getAllIds();
        foreach ($indexes as $index){
            $indexFactory = $this->indexFactory->create()->load($index);
            $indexFactory->reindexAll($index);
            $indexFactory->reindexRow($index);
        }
    }
}

?>