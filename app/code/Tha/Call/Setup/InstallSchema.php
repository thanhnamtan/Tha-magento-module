<?php
 
namespace Tha\Call\Setup;
 
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        
        $installer->startSetup();

        if (!$installer->tableExists('tha_tab_demo')) {
            $table = $installer->getConnection()
                                ->newTable( $installer->getTable('tha_tab_demo'))
                                ->addColumn(
                                    'post_id',
                                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                                    null,
                                    [
                                        'identity' => true,
                                        'nullable' => false,
                                        'primary' => true,
                                        'unsigned' => true,
                                    ],
                                    'Post ID'
                                )
                                ->addColumn(
                                    'name',
                                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                    255,
                                    ['nullable => false'],
                                    'Post Name'
                                )
                                ->addColumn(
                                    'post_content',
                                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                    '64k',
                                    [],
                                    'Post Post Content'
                                )
                                ->addColumn(
                                    'status',
                                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                                    1,
                                    [],
                                    'Post Status'
                                )
                                ->addColumn(
                                    'created_at',
                                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                                    null,
                                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                                    'Created At'
                                )
                                ->setComment('Post Table');
            $installer->getConnection()->createTable($table);
 
            $installer->getConnection()->addIndex(
                $installer->getTable('tha_tab_demo'),
                $setup->getIdxName(
                    $installer->getTable('tha_tab_demo'),
                    ['name', 'post_content'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['name', 'post_content'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }

        $installer->endSetup();
    }
}
?>