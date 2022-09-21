<?php
namespace Tha\Devob\Plugin\Framework;

use Magento\Framework\Module\Manager;

class ObjectManager
{
    protected $manager;

    public function __construct(
        Manager $manager
    )
    {
       $this->manager = $manager; 
    }

    public function beforeGet(\Magento\Framework\ObjectManagerInterface $objectManagerInterface, $input)
    {
        $module_manager = $this->manager;
        if ($module_manager->isEnabled("Tha_Devob")) {
            if ($input == "Jmango360\Japi\Helper\Product") {
                $input = "Tha\Devob\Helper\Devhl";
            }
        }
        return $input;
    }
}

?>