<?php


namespace Train\Blog\Model\ResourceModel\Category;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'category_id';
    protected $_eventPrefix = 'train_blog_category_collection';
    protected $_eventObject = 'category_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Train\Blog\Model\Category', 'Train\Blog\Model\ResourceModel\Category');
    }
}