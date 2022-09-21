<?php

namespace Tha\Call\Controller\Dev;

use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\ProductFactory as ResourceModelProductFactory;
use Magento\Framework\App\ActionInterface;

class prores implements ActionInterface
{

    protected $productFactory;
    protected $resourcePro;

    public function __construct(
        ProductFactory $productFactory,
        ResourceModelProductFactory $resourcePro
        // \Magento\Catalog\Model\ResourceModel\CategoryProduct $resourcePro//,
        // \Magento\Store\Model\ResourceModel\Store $store
        // \Magento\Catalog\Model\ResourceModel\CategoryProduct $categoryProduct
    )
    {
        $this->productFactory = $productFactory;
        $this->resourcePro = $resourcePro;
    }

    public function execute()
    {
        $productFactory = $this->productFactory->create();
        $resourcePro = $this->resourcePro->create();

        $pro = $resourcePro->load($productFactory, 6);
        // $pro = $productFactory->load(6);
        $attrs = $pro->getAttributesByCode();
        return 123;
        exit();
    }
}

?>