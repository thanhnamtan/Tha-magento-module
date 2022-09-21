<?php
namespace Tha\HideByGroup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class LangngheList implements ObserverInterface
{
    protected $collection;
    protected $scopeConfig;
    protected $customer_session;

    public function __construct(
        \Magento\Customer\Model\Session $customer_session,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Tha\HideByGroup\Model\ResourceModel\HideGroup\collection $collection
    )
    {
        $this->collection = $collection;
        $this->scopeConfig = $scopeConfig;
        $this->customer_session = $customer_session;
    }

    public function execute(Observer $observer)
    {
        $collection = $observer->getData("collection");
        if (!$this->scopeConfig->getValue("setting/hidegroup/enable", \Magento\Store\Model\ScopeInterface::SCOPE_STORE) || !$collection) {
            return $collection;
        }
        if ($collection) {
            $customer_session = $this->customer_session;
            if ($customer_session->isLoggedIn()) {
                $customer_group = (string) $customer_session->getCustomer()->getGroupId();
            }else {
                $customer_group = (string) \Magento\Customer\Model\Group::NOT_LOGGED_IN_ID;
            }
            
            if ($customer_group !== null) {
                $hide_by_group = $this->collection->addFieldToFilter("group_id", ["eq" => $customer_group]);
                if ($hide_by_group->getSize() == 1) {
                    $product_list = explode(",", $hide_by_group->getData()[0]["product_list"]);
                    if (count($product_list) > 0) {
                        $collection->addAttributeToFilter("entity_id", ["nin" => $product_list]);
                    }
                }
            }
        }
    }
}
?>