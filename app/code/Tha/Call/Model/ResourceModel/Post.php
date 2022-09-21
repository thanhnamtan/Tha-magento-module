<?php

namespace Tha\Call\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Post extends AbstractDb
{
    protected function _construct()
    {
        // truyền vào tên bảng và khóa chính
        $this->_init("tha_tab_demo", "post_id");   
    }
}

?>