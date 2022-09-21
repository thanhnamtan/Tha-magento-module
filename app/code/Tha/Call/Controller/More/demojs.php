<?php

namespace Tha\Call\Controller\More;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\View\Result\PageFactory;

class demojs implements ActionInterface
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
        $pageFactory->getConfig()->getTitle()->prepend(__("demo js control"));
        return $pageFactory;
    }
}


?>