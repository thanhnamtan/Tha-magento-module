<?php
 
namespace Tha\Trans\Controller\Adminhtml\Translate;
 
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
 
class Index extends \Magento\Backend\App\Action
{
    protected $_pageFactory;
 
    public function __construct(Action\Context $context, PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }
 
    public function execute()
    {
        $pagge = $this->_pageFactory->create();
        $pagge->getConfig()->getTitle()->prepend("tha trans menu");
        return $pagge;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tha_Trans::tran');
    }
}