<?php


namespace SmartOSC\Blog\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table= $installer->getConnection()
            ->newTable($installer->getTable('smartosc_categories'))
            ->addColumn('category_id',Table::TYPE_INTEGER,11, ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true])
            ->addColumn('category_name',Table::TYPE_TEXT,255,['nullable' => false])
            ->addColumn('category_description',Table::TYPE_TEXT, null,['nullable' => false])
            ->addColumn('category_path',Table::TYPE_TEXT,null,['nullable' => false])
            ->addColumn('status',Table::TYPE_SMALLINT,6, ['nullable' => false,'default' => 1])
            ->addColumn('created_time',Table::TYPE_DATE,null,[])
            ->setComment(" Catagories Table");
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('smartosc_blog'))
            ->addColumn('blog_id',Table::TYPE_INTEGER,11,['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true])
            ->addColumn('blog_name',Table::TYPE_TEXT,255,['nullable'=> false])
            ->addColumn('image',Table::TYPE_TEXT,null,['nullable' => false])
            ->addColumn('created_time',Table::TYPE_DATE,null,[])
            ->addColumn('updated_at',Table::TYPE_DATE,null,[])
            ->addColumn('start_time',Table::TYPE_DATE,null,[])
            ->addColumn('end_time',Table::TYPE_DATE,null,[])
            ->addColumn('url_youtube',Table::TYPE_TEXT,255,['nullable'=>false, 'default'=> null])
            ->addColumn('banner',Table::TYPE_TEXT,null,['nullable'=>false])
            ->addColumn('quote_banner',Table::TYPE_TEXT,null,['nullable' => false])
            ->addColumn('value',Table::TYPE_TEXT,null,['nullable' => false])
            ->addColumn('status',Table::TYPE_SMALLINT,6,['nullable' => false, 'default' =>1])
            ->setComment("Blog Table");
        $installer->getConnection()->createTable($table);

        $table= $installer->getConnection()
            ->newTable($installer->getTable('smartosc_blog_categories'))
            ->addColumn('blog_id',Table::TYPE_INTEGER,11,['unsigned' => true, 'nullable' => false, 'primary' => true])
            ->addColumn('category_id',Table::TYPE_INTEGER,11,['unsigned' => true, 'nullable' => false, 'primary' => true])
            ->addIndex(
                $installer->getIdxName('smartosc_blog_categories', ['blog_id']), ['blog_id']
            )
            ->addIndex(
                $installer->getIdxName('smartosc_blog_categories', ['category_id']), ['category_id']
            )
            ->addForeignKey(
                $installer->getFkName('smartosc_blog_categories', 'category_id', 'smartosc_categories', 'category_id'),
                'category_id', $installer->getTable('smartosc_categories'), 'category_id', Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('smartosc_blog_categories', 'blog_id', 'smartosc_blog', 'blog_id'), 'blog_id',
                $installer->getTable('smartosc_blog'), 'blog_id', Table::ACTION_CASCADE
            )
            ->setComment('Blog Categories');
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('smart_blog_category_store'))
            ->addColumn(
                'category_id', Table::TYPE_INTEGER, 11, ['unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn(
                'store_id', Table::TYPE_SMALLINT, 6, ['unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addIndex(
                $installer->getIdxName('smart_blog_category_store', ['store_id']), ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName('smart_blog_category_store', 'category_id', 'smartosc_categories', 'category_id'),
                'category_id', $installer->getTable('smartosc_categories'), 'category_id', Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('smart_blog_category_store', 'store_id', 'store', 'store_id'), 'store_id',
                $installer->getTable('store'), 'store_id', Table::ACTION_CASCADE
            )
            ->setComment('Category Store');
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('smartosc_blog_store'))
            ->addColumn(
                'blog_id', Table::TYPE_INTEGER, 11, ['unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn(
                'store_id', Table::TYPE_SMALLINT, 6, ['unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addIndex(
                $installer->getIdxName('smartosc_blog_store', ['store_id']), ['store_id']
            )
            ->addForeignKey(
                $installer->getFkName('smartosc_blog_store', 'blog_id', 'smartosc_blog', 'blog_id'), 'blog_id',
                $installer->getTable('smartosc_blog'), 'blog_id', Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('smartosc_blog_store', 'store_id', 'store', 'store_id'), 'store_id',
                $installer->getTable('store'), 'store_id', Table::ACTION_CASCADE
            )
            ->setComment('Blog Store');
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('smartosc_blog_product'))
            ->addColumn(
                'blog_id', Table::TYPE_INTEGER, 11, ['unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addColumn(
                'entity_id', Table::TYPE_INTEGER, 11, ['unsigned' => true, 'nullable' => false, 'primary' => true]
            )
            ->addIndex(
                $installer->getIdxName('smartosc_blog_product', ['blog_id']), ['blog_id']
            )
            ->addIndex(
                $installer->getIdxName('smartosc_blog_product', ['entity_id']), ['entity_id']
            )
            ->addForeignKey(
                $installer->getFkName('smartosc_blog_product', 'blog_id', 'smartosc_blog', 'blog_id'), 'blog_id',
                $installer->getTable('smartosc_blog'), 'blog_id', Table::ACTION_CASCADE
            )
            ->setComment('Blog Product');
        $installer->getConnection()->createTable($table);
    }
}