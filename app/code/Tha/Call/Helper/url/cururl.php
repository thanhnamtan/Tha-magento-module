<?php

namespace Tha\Call\Helper\url;

use Magento\Framework\App\Helper\AbstractHelper;

class cururl extends AbstractHelper
{
    protected $_storeManager;
    protected $urlInterface;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface
    )
    {
        $this->storeManager = $storeManager;
        $this->urlInterface = $urlInterface;
    }

    public function getName()
    {
        // getStore for context
        $currentStore = $this->_storeManager->getStore();

        $baseurl = $currentStore->getBaseUrl();
        //Output: https://example.com/

        $media = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        //Output: https://example.com/media/

        $static = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_STATIC);
        //Output: https://example.com/static/version1599299905/

        $url_build = $currentStore->getUrl('helloworld/post/index');
        //Output: https://example.com/helloworld/post/index/

        // return $currentStore;
    }

}



?>