<?php


namespace Smart\Bloger\Model;


class CatPost extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'smart_bloger_category_post';

    protected $_cacheTag = 'smart_bloger_category_post';

    protected $_eventPrefix = 'smart_bloger_category_post';

    protected function _construct()
    {
        $this->_init('Smart\Bloger\Model\ResourceModel\CatPost');
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