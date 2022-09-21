<?php
namespace Tha\Call\Model;

use Magento\Framework\Model\AbstractModel;

class Post extends AbstractModel
{
    protected function __construct()
    {
        // truyền vào resource_model
        $this->_init('Tha\Call\Model\ResourceModel\Post');   
    }
}

?>