<?php


namespace Train\Blog\Block\Frontend\Seach;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;

class Seach extends \Magento\Framework\View\Element\Template
{
    protected $postFactory;
    protected $collectionFactory;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Train\Blog\Model\PostFactory $postFactory,
        \Train\Blog\Model\CategoryFactory $categoryFactory,
        \Train\Blog\Model\ResourceModel\Post\CollectionFactory $collectionFactory

    )
    {
        $this->postFactory = $postFactory;
        $this->categoryFactory = $categoryFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function getPostCollection()
    {
        $post = $this->postFactory->create();
        return $post->getCollection();
    }
     public function Seach()
     {
         $keyword = $this->getRequest()->getParam('keyword');
         $collection = $this->collectionFactory->create();
       $collection->addFieldToFilter('post_name', array('like' => '%'.$keyword.'%'));
         return $collection;
     }
}