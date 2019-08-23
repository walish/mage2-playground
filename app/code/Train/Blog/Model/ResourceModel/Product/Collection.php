<?php


namespace Train\Blog\Model\ResourceModel\Product;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'product_id';
    protected $_eventPrefix = 'train_blog_product_collection';
    protected $_eventObject = 'product_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Train\Blog\Model\Product', 'Train\Blog\Model\ResourceModel\Product');
    }

}
