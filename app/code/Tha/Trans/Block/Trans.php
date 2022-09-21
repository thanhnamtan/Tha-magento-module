<?php
namespace Tha\Trans\Block;

use Magento\Framework\View\Element\Template;

class Trans extends Template
{
    protected $local_config;
    protected $locale;
    protected $trans_collection;

    public function __construct(
        \Magento\Framework\Locale\Config $local_config,
        \Magento\Config\Model\Config\Source\Locale $locale,
        \Tha\Trans\Model\ResourceModel\Trans\collectionFactory $trans_collection,
        \Magento\Framework\View\Element\Template\Context $context,
        $data = []
    )
    {
        $this->local_config = $local_config;
        $this->locale = $locale;
        $this->trans_collection = $trans_collection;
        parent::__construct($context, $data);
    }

    public function get_local_area()
    {
        return $this->local_config->getAllowedLocales();
    }

    public function local_config()
    {
        # code...
        $local_config = $this->locale->toOptionArray();
        return $local_config;
    }

    public function trans_collection()
    {
        return $this->trans_collection->create();
    }
}


?>