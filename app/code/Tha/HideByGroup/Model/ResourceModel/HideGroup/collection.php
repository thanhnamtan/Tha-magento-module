<?php

namespace Tha\HideByGroup\Model\ResourceModel\HideGroup;

class collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entity_id';
 
    protected function _construct()
    {
        // truyền vào model và resource_model
        $this->_init('Tha\HideByGroup\Model\HideGroup', 'Tha\HideByGroup\Model\ResourceModel\HideGroup');
    }
}

?>