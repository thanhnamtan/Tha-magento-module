<?php
 
namespace ViMagento\HelloWorld\Setup;
 
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
 
class InstallData implements InstallDataInterface
{
    protected $_postFactory;
 
    public function __construct(\Tha\Call\Model\PostFactory $postFactory)
    {
        $this->_postFactory = $postFactory;
    }
 
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $data = [
            'name'         => "Magento 2 thêm dữ liệu vào bảng vimagento_helloworld_post",
            'post_content' => "Content.",
            'status'       => 1
        ];
        $post = $this->_postFactory->create();
        $post->addData($data)->save();
    }
}
?>