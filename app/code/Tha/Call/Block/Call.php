<?php

namespace Tha\Call\Block;

use Magento\Framework\View\Element\Template;

class Call extends Template
{
    // public function __construct()
    // {
        
    // }

    public function data_value()
    {
        # code...
        return json_encode(['a' => 123, 'b' => 'pppp', 'c' => 6665]);
    }


}

?>