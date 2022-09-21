<?php

namespace Tha\Call\Controller\More;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\View\Result\PageFactory;

class ajaxdemo extends Action implements ActionInterface
{
    protected $pageFactory;
    public function __construct(
        Context $context,
        PageFactory $pageFactory
    )
    {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }
    
    public function execute()
    {
        $pageFactory = $this->pageFactory->create();
        $this->_eventManager->dispatch("demo_event", ['datax' => 123123]);
        $pageFactory->getConfig()->getTitle()->prepend(__(' tha ajaxdemo'));
        return $pageFactory;
    }
}

?>