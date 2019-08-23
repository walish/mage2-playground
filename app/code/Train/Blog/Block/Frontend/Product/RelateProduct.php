<?php


namespace Train\Blog\Block\Frontend\Product;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
class RelateProduct extends \Magento\Framework\View\Element\Template
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
    public function getFilterProduct($productId){
        $paramPost = $this->getRequest()->getParam('id');
        if($paramPost != ''){
            $a = explode(',',$productId);
            foreach ($a as $a){
                if ($a == $paramPost){
                    return true;
                }
            }
        }else{
            return true;
        }
    }

}