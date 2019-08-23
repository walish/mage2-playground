<?php


namespace Smart\Bloger\Model\ResourceModel\PostProduct;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'postproduct_id';
    protected $_eventPrefix = 'smart_bloger_post_product_collection';
    protected $_eventObject = 'postproduct_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Smart\Bloger\Model\PostProduct', 'Smart\Bloger\Model\ResourceModel\PostProduct');
    }

}