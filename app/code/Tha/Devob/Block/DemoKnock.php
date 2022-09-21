<?php

namespace Tha\Devob\Block;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class DemoKnock extends Template{

    protected $objectManagerInterface;
    protected $redirect;
    protected $response;

    public function __construct(
        ObjectManagerInterface $objectManagerInterface,
        \Magento\Framework\App\Response\Http $response,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        Context $context,
        $data = []
    )
    {
        $this->objectManagerInterface = $objectManagerInterface;
        $this->redirect = $redirect;
        $this->response = $response;
        parent::__construct($context, $data);
    }

    public function get_object(string $object_class, $params = [])
    {
        if (class_exists($object_class)) {
            return $this->objectManagerInterface->create($object_class, $params);
        }else {
            return null;
        }
    }

    public function redirect(string $url)
    {
        $this->redirect->redirect($this->response, $url);
    }
}


?>