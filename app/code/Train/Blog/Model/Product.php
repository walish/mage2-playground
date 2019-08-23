<?php


namespace Train\Blog\Model;


class Product extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'train_blog_product';

    protected $_idFieldName = 'product_id';

    protected $_cacheTag = 'train_blog_product';

    protected $_eventPrefix = 'train_blog_product';

    protected function _construct()
    {
        $this->_init('Train\Blog\Model\ResourceModel\Product');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}


