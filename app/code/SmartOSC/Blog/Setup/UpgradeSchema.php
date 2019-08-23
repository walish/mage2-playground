<?php


namespace SmartOSC\Blog\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;
        $setup->startSetup();

        $version = $context->getVersion();
        $connection = $setup->getConnection();
        if (version_compare($context->getVersion(), '1.0.1', '<')) {

            $setup->getConnection();
            $setup->getConnection()->addColumn(
                $setup->getTable('smartosc_categories'),
                'parent_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => true,
                    'unsigned' => true,
                    'default' => 0,
                    'size' => null,
                    'comment' => 'Parent Id',

                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('smartosc_categories'),
                'position',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => false,
                    'unsigned' => true,
                    'default' => 0,
                    'size' => null,
                    'comment' => 'Position',

                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('smartosc_categories'),
                'level',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => false,
                    'unsigned' => true,
                    'default' => 0,
                    'size' => null,
                    'comment' => 'Position',

                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable('smartosc_categories'),
                'children_count',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => false,
                    'unsigned' => true,
                    'default' => 0,
                    'size' => null,
                    'comment' => 'Children count',

                ]
            );
            $setup->endSetup();
        }

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $setup->getConnection();
            $setup->getConnection()->addColumn(
                $setup->getTable('smartosc_blog'),
                'short_content',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'size' => null,
                    'comment' => 'Short Content',
                ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            foreach (['smartosc_categories'] as $table) {
                $table = $setup->getTable($table);
                $connection->addColumn(
                    $setup->getTable($table),
                    'meta_keywords',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => '64k',
                        'nullable' => true,
                        'comment' => 'Category Meta Keywords',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'meta_description',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => '64k',
                        'nullable' => true,
                        'comment' => 'Category Meta Description',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'identifier',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 100,
                        'nullable' => true,
                        'default' => null,
                        'comment' => 'Category String Identifier',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'content_heading',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => '2M',
                        'comment' => 'content_heading',

                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'path',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => true,
                        'comment' => 'path',

                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'position',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        'length' => null,
                        'nullable' => false,
                        'comment' => 'position',

                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'is_active',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        'length' => null,
                        'nullable' => false,
                        'default' => '1',
                        'comment' => 'Is Category Active',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'page_layout',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => true,
                        'comment' => 'Post Layout',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'layout_update_xml',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => '64k',
                        'nullable' => true,
                        'comment' => 'Post Layout Update Content',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'custom_theme',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 100,
                        'nullable' => true,
                        'comment' => 'Post Custom Theme',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'custom_layout',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => true,
                        'comment' => 'Post Custom Template',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'custom_layout_update_xml',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => '64k',
                        'nullable' => true,
                        'comment' => 'Post Custom Layout Update Content',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'custom_theme_from',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                        'nullable' => true,
                        'comment' => 'Post Custom Theme Active From Date',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'custom_theme_to',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
                        'nullable' => true,
                        'comment' => 'Post Custom Theme Active To Date',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'meta_title',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 255,
                        'nullable' => true,
                        'comment' => 'Category Meta Title',
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'include_in_menu',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        null,
                        ['nullable' => false, 'default' => '0'],
                        'comment' => 'Category In Menu',
                        'after' => 'position'
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'display_mode',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        null,
                        'nullable' => false,
                        'default' => '0',
                        'comment' => 'Display Mode',
                        'after' => 'is_active'
                    ]
                );

                $connection->addColumn(
                    $setup->getTable($table),
                    'posts_sort_by',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        null,
                        'nullable' => false,
                        'default' => '0',
                        'comment' => 'Post Sort By',
                        'after' => 'position'
                    ]
                );
            }



            }
        if (version_compare($context->getVersion(), '1.0.8', '<')) {
            $setup->getConnection();
            $setup->getConnection()->addColumn(
                $setup->getTable('smartosc_blog'),
                'category_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'nullable' => false,
                    'unsigned' => true,
                    'size' => 11,
                    'comment' => 'Category Id',
                ]
            );


        }
//        if (version_compare($context->getVersion(), '1.0.9', '<')) {
//            $table = $setup->getConnection()->newTable(
//            $setup->getTable('smartosc_blog')
//            )->addIndex(
//                $installer->getIdxName('smartosc_blog', ['category_id']), ['category_id']
//            )->addForeignKey(
//                $installer->getFkName('smartosc_blog', 'category_id', 'smartosc_categories', 'category_id'),
//                'category_id',
//                $installer->getTable('smartosc_categories'),
//                'category_id',
//                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
//            );
//            $setup->getConnection()->createTable($table);
//        }
    }
}