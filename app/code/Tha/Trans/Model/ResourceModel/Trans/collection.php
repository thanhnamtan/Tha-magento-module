<?php
namespace Tha\Trans\Model\ResourceModel\Trans;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected function _construct()
    {
        $this->_init("Tha\Trans\Model\Trans", "Tha\Trans\Model\ResourceModel\Trans");
    }
}

?>