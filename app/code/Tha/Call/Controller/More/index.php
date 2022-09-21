<?php

namespace THa\Call\Controller\More;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\View\Result\PageFactory;

class index implements ActionInterface
{
    protected $pageFactory;

    public function __construct(
        PageFactory $pageFactory
    )
    {
        $this->pageFactory = $pageFactory;    
    }

    public function execute()
    {
        $pageFactory = $this->pageFactory->create();
        $pageFactory->getConfig()->setMetaTitle("tha call more");
        return $pageFactory;
    }
    
}

?>