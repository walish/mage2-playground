<?php


namespace Train\Blog\Block\Frontend\Content;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Registry\Magento\Framework\Registry;

class Detail extends \Magento\Framework\View\Element\Template
{
    protected $registry;
    protected $postFactory;
    protected $categoryFactory;
    protected $collectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Train\Blog\Model\PostFactory $postFactory,
        \Train\Blog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Registry $registry,
        \Train\Blog\Model\ResourceModel\Post\CollectionFactory $collectionFactory

    )
    {
        $this->postFactory = $postFactory;
        $this->categoryFactory = $categoryFactory;
        $this->registry = $registry;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function getPostCollection()
    {
        $collection = $this->collectionFactory->create();
        return $collection;
    }

    public function getPost()
    {
        $postId = $this->getRequest()->getParam('id');
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('post_id', ['eq', $postId]);
        return $collection->getLastItem();
    }

}