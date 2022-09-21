<?php

namespace Tha\HideByGroup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Langnghe implements ObserverInterface
{
    protected $hide_group;
    protected $collection;

    public function __construct(
        \Tha\HideByGroup\Model\HideGroupFactory $hide_group,
        \Tha\HideByGroup\Model\ResourceModel\HideGroup\collectionFactory $collection
    )
    {
        $this->hide_group = $hide_group;
        $this->collection = $collection;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getData("product");
        if ($product->getData("hide_group") !== null) {
            $group_value = explode(",", $product->getData("hide_group"));
            for ($i=0; $i < count($group_value); $i++) { 
                $hide_groups = $this->collection->create()->addFieldToFilter("group_id", ['eq' => $group_value[$i]]);
                if ($hide_groups->getSize() == 1) {
                    $hide_group = $this->hide_group->create();
                    $hide_group->load((int)$hide_groups->getData()[0]["entity_id"]);
                    if (in_array($product->getId(), array_values(explode(",", $hide_group->getData("product_list"))))) {
                        continue;
                    }
                    $hide_group->setData("product_list", $hide_group->getData("product_list").",".$product->getId());
                    $hide_group->save();
                }else {
                    $hide_group = $this->hide_group->create();
                    $hide_group->setData("group_id", (string) $group_value[$i]);
                    $hide_group->setData("product_list", (string) $product->getId());
                    $hide_group->save();
                }
            }
        }
    }
}

?>