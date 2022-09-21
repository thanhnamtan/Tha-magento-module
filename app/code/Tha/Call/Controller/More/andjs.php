<?php

namespace THa\Call\Controller\More;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\View\Result\PageFactory;
use stdClass;

class andjs implements ActionInterface
{

    protected $pageFactory;
    protected $context;

    public function __construct(
        PageFactory $pageFactory,
        Context $context
    )
    {
        $this->context = $context;
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        // $a là 1 object
        $a = new stdClass();
        $a->x = 123;

        // $a = [123, 'ppp'];

        echo("gia tri cua a truoc khi chay event la:");
        print_r((array)$a);
        $event = $this->context->getEventManager();
        $event->dispatch("demo_event2", ['a' => $a]);
        echo("gia tri cua a sau khi chay event la: ");
        print_r((array)$a);
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->prepend(__('demo js component'));
        return $page;
    }


}

?>