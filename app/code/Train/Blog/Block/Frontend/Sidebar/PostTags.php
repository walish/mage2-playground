<?php


namespace Train\Blog\Block\Frontend\Sidebar;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;


class PostTags extends \Magento\Framework\View\Element\Template
{
    protected $postFactory;
    protected $collectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Train\Blog\Model\PostFactory $postFactory,
        \Train\Blog\Model\CategoryFactory $categoryFactory,
        \Train\Blog\Model\ResourceModel\Post\Collection $collectionFactory
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

   
}