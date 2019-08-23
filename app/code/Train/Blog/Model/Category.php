<?php


namespace Train\Blog\Model;


class Category extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'train_blog_category';

    protected $_cacheTag = 'train_blog_category';

    protected $_eventPrefix = 'train_blog_category';

    protected function _construct()
    {
        $this->_init('Train\Blog\Model\ResourceModel\Category');
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