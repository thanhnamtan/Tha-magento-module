<?php
namespace Tha\Trans\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $install = $setup;

        $install->startSetup();
        if (!$install->tableExists("tha_trans")) {
            $table = $setup->getConnection()->newTable($install->getTable("tha_trans"))
                                            ->addColumn(
                                                "entity_id",
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
                                                'local_area',
                                                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                                255,
                                                ['nullable' => false],
                                                'local area id'
                                            )
                                            ->addColumn(
                                                'trans_key',
                                                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                                '64k',
                                                ['nullable' => true],
                                                'tran text before'
                                            )
                                            ->addColumn(
                                                'trans_value',
                                                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                                "64k",
                                                ['nullable' => true],
                                                'tran text after'
                                            )
                                            ->addColumn(
                                                'created_at',
                                                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                                                null,
                                                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                                                'Created At'
                                            )
                                            ->setComment('hide group Table');
            $install->getConnection()->createTable($table);
            $install->endSetup();
        }
    }
}

?>