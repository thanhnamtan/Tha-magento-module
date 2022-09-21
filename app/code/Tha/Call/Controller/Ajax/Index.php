<?php
 
namespace Tha\Call\Controller\Ajax;
 
class Index extends \Magento\Framework\App\Action\Action
{
    protected $json;
    protected $resultJsonFactory;
 
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $json,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        $this->json = $json;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }
 
    public function execute()
    {
        $data = $this->getRequest()->getContent();
        $response = $this->json->unserialize($data);
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }
}