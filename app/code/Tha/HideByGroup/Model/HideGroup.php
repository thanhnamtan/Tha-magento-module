<?php

namespace Tha\HideByGroup\Model;

use Magento\Framework\Model\AbstractModel;

class HideGroup extends AbstractModel
{
    public function _construct()
    {
        // resource model name
        $this->_init('Tha\HideByGroup\Model\ResourceModel\HideGroup');   
    }
}

?>