<?php


namespace Smart\Bloger\Model\ResourceModel\Category;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'category_id';
    protected $_eventPrefix = 'smart_bloger_category_collection';
    protected $_eventObject = 'category_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Smart\Bloger\Model\Category', 'Smart\Bloger\Model\ResourceModel\Category');
    }

}