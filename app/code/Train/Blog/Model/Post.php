<?php


namespace Train\Blog\Model;


class   Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'train_blog_post';

    protected $_idFieldName = 'post_id';

    protected $_cacheTag = 'train_blog_post';

    protected $_eventPrefix = 'train_blog_post';

    protected function _construct()
    {
        $this->_init('Train\Blog\Model\ResourceModel\Post');
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