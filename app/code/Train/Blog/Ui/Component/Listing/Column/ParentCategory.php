<?php

namespace Train\Blog\Ui\Component\Listing\Column;

class ParentCategory extends \Magento\Framework\View\Element\Template implements \Magento\Framework\Option\ArrayInterface
{
    protected $categoryFactory;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Train\Blog\Model\CategoryFactory $categoryFactory)
    {
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    public function toOptionArray()
    {
        $category = $this->categoryFactory->create();
        $collection = $category->getCollection();
        $array = [];
        foreach ($collection as $key => $value) {
            $array[] = ['value' => $key, 'label' => $value->getCategoryName()];
        }
        return $array;
    }
}