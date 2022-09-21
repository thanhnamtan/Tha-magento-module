<?php

namespace Tha\Trans\Model;

use Magento\Framework\Model\AbstractModel;

class Trans extends AbstractModel
{
    public function _construct()
    {
        $this->_init("Tha\Trans\Model\ResourceModel\Trans");
    }
}

?>