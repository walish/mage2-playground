<?php


namespace Smart\Bloger\Model;


class Category extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'smart_bloger_category';

    protected $_cacheTag = 'smart_bloger_category';

    protected $_eventPrefix = 'smart_bloger_category';

    protected function _construct()
    {
        $this->_init('Smart\Bloger\Model\ResourceModel\Category');
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