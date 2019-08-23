<?php


namespace Smart\Bloger\Model;


class PostProduct extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'smart_bloger_post_product';

    protected $_cacheTag = 'smart_bloger_post_product';

    protected $_eventPrefix = 'smart_bloger_post_product';

    protected function _construct()
    {
        $this->_init('Smart\Bloger\Model\ResourceModel\PostProduct');
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