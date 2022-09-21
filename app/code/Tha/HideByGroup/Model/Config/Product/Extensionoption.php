<?php
 
namespace Tha\HideByGroup\Model\Config\Product;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
 
class Extensionoption extends AbstractSource
{
    protected $groupManagement;

    public function __construct(
        \Magento\Customer\Model\ResourceModel\Group\Collection $groupManagement
    )
    {
        $this->groupManagement = $groupManagement;
    }
    
    public function getAllOptions()
    {
        return $this->format_group_data();
    }

    protected function format_group_data()
    {
        return $this->groupManagement->toOptionArray();
    }
}