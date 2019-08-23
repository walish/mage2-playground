<?php

namespace Train\Blog\Ui\Component\Listing\Column;

class ParentProduct extends \Magento\Framework\View\Element\Template implements \Magento\Framework\Option\ArrayInterface
{
    protected $productFactory;

    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Train\Blog\Model\ProductFactory $productFactory)
    {
        $this->productFactory = $productFactory;
        parent::__construct($context);
    }

    public function toOptionArray()
    {
        $product = $this->productFactory->create();
        $collection = $product->getCollection();
        $array = [];
        foreach ($collection as $key => $value) {
            $array[] = ['value' => $key, 'label' => $value->getProductName()];
        }
        return $array;
    }
}