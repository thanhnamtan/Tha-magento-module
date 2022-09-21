<?php
 
namespace Tha\Call\Model\ResourceModel\Post;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'post_id';
 
    protected function _construct()
    {
        // truyền vào model và resource_model
        $this->_init('Tha\Call\Model\Post', 'Tha\Call\Model\ResourceModel\Post');
    }
}