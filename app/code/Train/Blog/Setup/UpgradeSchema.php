<?php


namespace Train\Blog\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {

        if(version_compare($context->getVersion(), '1.0.1', '<')) {
           $connection = $setup->getConnection();
           $connection->addColumn(
                $setup->getTable( 'train_blog_post' ),
                'product_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'comment' => 'Product Id',
                    'after' => 'category_id',
                ]
            );
        }
    }
}