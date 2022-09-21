<?php
namespace Tha\Trans\Plugin\Magento\Framework;

class Translate extends \Magento\Framework\Translate
{
    protected $translate_collection;

    public function __construct(
        \Tha\Trans\Model\ResourceModel\Trans\collectionFactory $translate_collection
    )
    {
        $this->translate_collection = $translate_collection;
    }

    public function afterGetData(\Magento\Framework\Translate $translate, $result)
    {
        $collection_data = $this->translate_collection->create()->addFieldToFilter("local_area", $translate->getLocale());
        if ($collection_data->getSize()) {
            $collections = $collection_data->toArray()["items"];
            for ($i=0; $i < count($collections); $i++) { 
                if ($collections[$i]["trans_key"] == $collections[$i]["trans_value"]) {
                    continue;
                }
                $result[$collections[$i]["trans_key"]] = $collections[$i]["trans_value"];
            }
        }
         return $result;
    }
}

?>