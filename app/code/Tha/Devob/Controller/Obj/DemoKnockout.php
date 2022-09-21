<?php
namespace Tha\Devob\Controller\Obj;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\View\Result\PageFactory;

class DemoKnockout implements ActionInterface{
    protected $pageFactory;

    function __construct(
        PageFactory $pageFactory
    )
    {
        $this->pageFactory = $pageFactory;
    }

    public function execute()
    {
        $page = $this->pageFactory->create();
        $page->getConfig()->getTitle()->prepend("tha demo knock");
        return $page;
    }
}

?>
