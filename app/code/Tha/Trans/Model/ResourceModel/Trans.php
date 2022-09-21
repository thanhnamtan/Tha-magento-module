<?php
namespace Tha\Trans\Model\ResourceModel;

class Trans extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init("tha_trans", "entity_id");
    }
}

?>