<?php


namespace SmartOSC\Blog\Helper;


use Magento\Framework\App\Helper\Context;

class Category extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $categoryCollectionFactory;
    
     public function __construct(
         \SmartOSC\Blog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
          Context $context)
     {
         $this->categoryCollectionFactory = $categoryCollectionFactory;
         parent::__construct($context);
     }
    public function getRootCategory()
    {
        $category   = false;
        $collection = $this->categoryCollectionFactory->create()
            ->addFieldToFilter('parent_id', 0);

        if ($collection->count()) {
            $category = $collection->getFirstItem();
        }

        return $category;
    }
}