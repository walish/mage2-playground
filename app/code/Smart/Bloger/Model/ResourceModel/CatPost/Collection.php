<?php


namespace Smart\Bloger\Model\ResourceModel\CatPost;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'categorypost_id';
    protected $_eventPrefix = 'smart_bloger_category_post_collection';
    protected $_eventObject = 'catpost_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Smart\Bloger\Model\CatPost', 'Smart\Bloger\Model\ResourceModel\CatPost');
    }

}