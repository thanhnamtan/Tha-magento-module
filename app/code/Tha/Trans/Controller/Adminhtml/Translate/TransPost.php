<?php
 
namespace Tha\Trans\Controller\Adminhtml\Translate;
 
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
use Magento\Framework\HTTP\ResponseFactory;

 
class TransPost extends \Magento\Backend\App\Action
{
    protected $_pageFactory;
    protected $transFactory;
    protected $responseFactory;
    protected $jsonFactory;
 
    public function __construct(
        \Tha\Trans\Model\TransFactory $transFactory,
        ResponseFactory $responseFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        Action\Context $context, 
        PageFactory $pageFactory
        )
    {
        $this->_pageFactory = $pageFactory;
        $this->transFactory = $transFactory;
        $this->responseFactory = $responseFactory;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
    }
 
    public function execute()
    {
        $params = $this->_request->getParams();
        $jsonFactory = $this->jsonFactory->create();

        if ($this->_request->isDelete()) {
            $body = $this->_request->getContent();
            
            if ($body) {
               $params = json_decode($body, true);
                
            }else {
                throw new \Magento\Framework\Exception\InputException(
                    __(\Magento\Framework\Exception\InputException::DEFAULT_MESSAGE), null, \Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR
                );
            }
            try {
                if ($id = $params["entity_id"]) {
                    $transFactory = $this->transFactory->create();
                    $delete_ob = $transFactory->load($id);
                    $res_data = $delete_ob->delete();
                    
                    return $jsonFactory->setData($transFactory->toJson());
                }
            } catch (\Throwable $th) {
                throw new \Magento\Framework\Exception\InputException(
                    __(\Magento\Framework\Exception\InputException::DEFAULT_MESSAGE), null, \Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR
                 );
            }
            
        }elseif ($this->_request->isPost()) {
            if ($params["local_area"] && $params["be_trasn"] && $params["af_trasn"]) {
                try {
                    $transFactory = $this->transFactory->create();
                    $transFactory->setData("local_area", $params["local_area"]);
                    $transFactory->setData("trans_key", $params["be_trasn"]);
                    $transFactory->setData("trans_value", $params["af_trasn"]);
                    $transFactory->save();
    
                    return $jsonFactory->setData($transFactory->toJson());
                } catch (\Throwable $th) {
                    throw new \Magento\Framework\Webapi\Exception(
                        __($th->getMessage()), 0, \Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR
                    );
                }
                
            }else {
                throw new \Magento\Framework\Exception\InputException(
                   __(\Magento\Framework\Exception\InputException::DEFAULT_MESSAGE), null, \Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR
                );
            }
        }elseif ($this->_request->isPut()) {
            if (($params = json_decode($this->_request->getContent(), true)) && isset($params["entity_id"]) && !empty($params["entity_id"])) {
               try {
                $enity_id = $params["entity_id"];
                $transFactory = $this->transFactory->create()->load($enity_id);
                $transFactory->setData("local_area", $params["local_area"]);
                $transFactory->setData("trans_key", $params["be_trasn"]);
                $transFactory->setData("trans_value", $params["af_trasn"]);
                $transFactory->save();
                return $jsonFactory->setData($transFactory->toJson()); 
               } catch (\Throwable $th) {
                throw new \Magento\Framework\Webapi\Exception(
                    __($th->getMessage()), 0, \Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR
                );
               }
            }
        }

        throw new \Magento\Framework\Webapi\Exception(
            __("get method not to use in the process."), 0, \Magento\Framework\Webapi\Exception::HTTP_INTERNAL_ERROR
        );
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tha_Trans::tran');
    }
}