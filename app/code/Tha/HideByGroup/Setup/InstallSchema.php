<?php
namespace Tha\HideByGroup\Setup;
 
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        
        $installer->startSetup();

        if (!$installer->tableExists('tha_hide_group')) {
            $table = $installer->getConnection()
                                ->newTable( $installer->getTable('tha_hide_group'))
                                ->addColumn(
                                    'entity_id',
                                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                                    null,
                                    [
                                        'identity' => true,
                                        'nullable' => false,
                                        'primary' => true,
                                        'unsigned' => true,
                                    ],
                                    'ID'
                                )
                                ->addColumn(
                                    'group_id',
                                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                    255,
                                    ['nullable' => false],
                                    'group id'
                                )
                                ->addColumn(
                                    'group_name',
                                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                    '64k',
                                    ['nullable' => true],
                                    'group name'
                                )->addColumn(
                                    'product_list',
                                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                    '64k',
                                    ['nullable' => true],
                                    'product list'
                                )
                                ->addColumn(
                                    'created_at',
                                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                                    null,
                                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                                    'Created At'
                                )
                                ->setComment('hide group Table');
            $installer->getConnection()->createTable($table);
 
            $installer->getConnection()->addIndex(
                $installer->getTable('tha_hide_group'),
                $setup->getIdxName(
                    $installer->getTable('tha_hide_group'),
                    ['group_name', 'group_id'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['group_name', 'group_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }

        $installer->endSetup();
    }
}
?>