<?php


namespace Smart\Bloger\Model\ResourceModel\Product;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'product_id';
    protected $_eventPrefix = 'smart_bloger_product_collection';
    protected $_eventObject = 'product_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Smart\Bloger\Model\Product', 'Smart\Bloger\Model\ResourceModel\Product');

    }

}