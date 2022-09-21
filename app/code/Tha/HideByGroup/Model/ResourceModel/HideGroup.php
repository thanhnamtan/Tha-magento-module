<?php

namespace Tha\HideByGroup\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class HideGroup extends AbstractDb
{
    protected function _construct()
    {
        // truyền vào tên bảng và khóa chính
        $this->_init("tha_hide_group", "entity_id");   
    }
}

?>