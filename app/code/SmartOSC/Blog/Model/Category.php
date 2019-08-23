<?php


namespace SmartOSC\Blog\Model;

use Magento\Framework\Model\AbstractModel;

class Category extends AbstractModel
{

    const CATEGORY_ID = 'category_id';

    protected $_eventPrefix = 'blogs';
    protected $_eventObject = 'category';
    protected $_idFieldName = self::CATEGORY_ID;


    protected function _construct()
    {
        $this->_init( 'SmartOSC\Blog\Model\ResourceModel\Category');
    }
}