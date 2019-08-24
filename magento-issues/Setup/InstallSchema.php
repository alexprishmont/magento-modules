<?php

namespace Alexpr\IssuesHandler\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup->startSetup();
        $this->createTable($installer);
    }

    protected function createTable(SchemaSetupInterface $installer)
    {
        $table = $installer->getConnection()
            ->newTable('issues_list')
            ->addColumn(
                'entity_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn(
                'sender_name',
                Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 'none']
            )
            ->addColumn(
                'sender_email',
                Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 'none']
            )
            ->addColumn(
                'issue',
                Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 'none']
            )
            ->addColumn(
                'status',
                Table::TYPE_TEXT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 'new']
            )
            ->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE]
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => Table::TIMESTAMP_INIT]
            );

        $installer->getConnection()->createTable($table);
    }
}
