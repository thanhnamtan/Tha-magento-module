<?php

namespace Tha\Devob\Controller\Obj;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\ObjectManagerInterface;

class index implements ActionInterface
{
    protected $product;
    protected $objectManagerInterface;
    protected $resultFactory;


    public function __construct(
        \Jmango360\Japi\Helper\Product $product,
        ObjectManagerInterface $objectManagerInterface,
        ResultFactory $resultFactory
    )
    {
        $this->product = $product;
        $this->objectManagerInterface = $objectManagerInterface;
        $this->resultFactory = $resultFactory;
    }

    public function execute()
    {
        // $objectManagerInterface = $this->objectManagerInterface;
        // var_dump(get_class($objectManagerInterface->get("Jmango360\Japi\Helper\Product")));
        // var_dump(get_class($this->product));
        // die();

        $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $result->setPath("/");
        return $result;

        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result->setData(["a" => 123, "status" => 1, "name" => "product name"]);
        return $result;
    }

}
?>