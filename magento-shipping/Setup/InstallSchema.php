<?php

namespace Alexpr\SimpleShipping\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup->startSetup();
        $this->createTableWithForeignKeys($installer);
    }

    protected function createTableWithForeignKeys(SchemaSetupInterface $installer)
    {
        $table = $installer->getConnection()
            ->newTable('api_orders_ids')
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn(
                'order_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 0]
            )
            ->addColumn(
                'api_order_id',
                Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 0]
            )
            ->addIndex(
                $installer->getIdxName(
                    'api_orders_ids',
                    ['order_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['order_id'],
                AdapterInterface::INDEX_TYPE_UNIQUE
            )
            ->addForeignKey(
                $installer->getFkName(
                    'api_orders_ids',
                    'order_id',
                    'sales_order',
                    'entity_id'
                ),
                'order_id',
                $installer->getTable('sales_order'),
                'entity_id',
                Table::ACTION_CASCADE
            );
        $installer->getConnection()->createTable($table);
    }
}
