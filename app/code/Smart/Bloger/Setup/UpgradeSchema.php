<?php


namespace Smart\Bloger\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;

        $installer->startSetup();

        if(version_compare($context->getVersion(), '1.2.0', '<')) {
            if (!$installer->tableExists('smart_bloger_category')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('smart_bloger_category')
                )
                    ->addColumn(
                        'category_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'Category ID'
                    )
                    ->addColumn(
                        'parent_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        20,
                        [],
                        'Parent ID'
                    )
                    ->addColumn(
                        'post_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        20,
                        [],
                        'Post ID'
                    )
                    ->addColumn(
                        'category_name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Category Name'
                    )
                    ->addColumn(
                        'category_created',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Category Created'
                    )->addColumn(
                        'category_updated',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                        'Category Updated')
                    ->setComment('Categories Table');
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addIndex(
                    $installer->getTable('smart_bloger_category'),
                    $setup->getIdxName(
                        $installer->getTable('smart_bloger_category'),
                        ['category_name'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['category_name'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }

            if (!$installer->tableExists('smart_bloger_post')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('smart_bloger_post')
                )
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
                        'category_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => false,
                            'unsigned' => true,
                        ],
                        'Category ID'
                    )
                    ->addColumn('post_name', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable => false'], 'Post Name')
                    ->addForeignKey(
                        $installer->getFkName(
                            'smart_bloger_post',
                            'category_id',
                            'smart_bloger_category',
                            'category_id'
                        ),
                        'category_id',
                        $installer->getTable('smart_bloger_category'),
                        'category_id',
                        Table::ACTION_CASCADE
                    )
                    ->addColumn(
                        'post_url',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Post URL'
                    )
                    ->addColumn(
                        'post_title',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Post Title'
                    )
                    ->addColumn(
                        'post_shortdescription',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Post Short Description'
                    )
                    ->addColumn(
                        'post_description',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Post Description'
                    )
                    ->addColumn(
                        'post_tags',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Post Tags'
                    )
                    ->addColumn(
                        'post_status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        [],
                        'Post Status'
                    )
                    ->addColumn(
                        'post_image',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Post Image'
                    )
                    ->addColumn(
                        'post_created',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Post Created'
                    )->addColumn(
                        'post_updated',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                        'Post Updated')
                    ->setComment('Post Table');
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addIndex(
                    $installer->getTable('smart_bloger_post'),
                    $setup->getIdxName(
                        $installer->getTable('smart_bloger_post'),
                        ['post_name', 'post_url', 'post_title', 'post_tags', 'post_image'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['post_name', 'post_url', 'post_title', 'post_tags', 'post_image'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }

            if (!$installer->tableExists('smart_bloger_product')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('smart_bloger_product')
                )
                    ->addColumn(
                        'product_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary' => true,
                            'unsigned' => true,
                        ],
                        'Product ID'
                    )
                    ->addColumn(
                        'post_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => false,
                            'unsigned' => true,
                        ],
                        'Post ID'
                    )
                    ->addColumn(
                        'category_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => false,
                            'unsigned' => true,
                        ],
                        'Category ID'
                    )
                    ->addForeignKey(
                        $installer->getFkName(
                            'smart_bloger_product',
                            'post_id',
                            'smart_bloger_post',
                            'post_id'
                        ),
                        'post_id',
                        $installer->getTable('smart_bloger_post'),
                        'post_id',
                        Table::ACTION_CASCADE
                    )
                    ->addForeignKey(
                        $installer->getFkName(
                            'smart_bloger_product',
                            'category_id',
                            'smart_bloger_category',
                            'category_id'
                        ),
                        'category_id',
                        $installer->getTable('smart_bloger_category'),
                        'category_id',
                        Table::ACTION_CASCADE
                    )
                    ->addColumn(
                        'product_name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Product Name'
                    )
                    ->addColumn(
                        'product_url',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Product URL'
                    )
                    ->addColumn(
                        'product_price',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        255,
                        [],
                        'Product Price'
                    )
                    ->addColumn(
                        'product_description',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Product Description'
                    )
                    ->addColumn(
                        'product_viewcount',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        20,
                        [],
                        'Product View Count'
                    )
                    ->addColumn(
                        'product_quantity',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        255,
                        [],
                        'Product Quantity'
                    )
                    ->addColumn(
                        'product_size',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        255,
                        [],
                        'Product Size'
                    )
                    ->addColumn(
                        'product_Color',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Product Color'
                    )
                    ->addColumn(
                        'product_status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        [],
                        'Product Status'
                    )
                    ->addColumn(
                        'product_tags',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Product Tags'
                    )
                    ->addColumn(
                        'product_image',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Product Image'
                    )
                    ->addColumn(
                        'product_created',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                        'Product Created'
                    )->addColumn(
                        'product_updated',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                        'Product Updated')
                    ->setComment('Product Table');
                $installer->getConnection()->createTable($table);

                $installer->getConnection()->addIndex(
                    $installer->getTable('smart_bloger_product'),
                    $setup->getIdxName(
                        $installer->getTable('smart_bloger_product'),
                        ['product_name', 'product_url', 'product_Color', 'product_tags', 'product_image'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['product_name', 'product_url', 'product_Color', 'product_tags', 'product_image'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }
        }

        $installer->endSetup();
    }

}