<?php


namespace Train\Blog\Block\Frontend\Product;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
class ListProduct extends \Magento\Framework\View\Element\Template
{
    protected $collectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Train\Blog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function getProductCollection()
    {
        $collection = $this->collectionFactory->create();
        return $collection;
    }

}
