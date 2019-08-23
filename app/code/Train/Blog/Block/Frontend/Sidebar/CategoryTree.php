<?php


namespace Train\Blog\Block\Frontend\Sidebar;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;

class CategoryTree extends \Magento\Framework\View\Element\Template
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

    public function getTree()
    {
        $category = $this->categoryFactory->create();
        return $category->getCollection();
    }

    public function getId()
    {
        $model = $this->categoryFactory->create();
        if ($this->getRequest()->getParam('id')) {
            $model->load($this->getRequest()->getParam('id'));
        }
        return $model;
    }

    public function CategoryPost($categoryId)
    {
        $paramCategory = $this->getRequest()->getParam('category');

        $postCategory = explode(',', $categoryId);

        foreach ($postCategory as $postCategory) {
            if ($postCategory == $paramCategory) {
                return true;
            }
        }
    }

    public function getPostCollection()
    {
        $post = $this->postFactory->create();
        return $post->getCollection();
    }

    public function showCategories($categories, $parent_id = 0)
    {
        $cate_child = array();
        foreach ($categories as $key => $item) {
            if ($item['parent_id'] == $parent_id) {
                $cate_child[] = $item;
                unset($categories[$key]);
            }
        }
        if ($cate_child) {
            echo '<ul>';
            foreach ($cate_child as $key => $item) {
                echo '<li>'. '<a href=" '.$this->getUrl('*/*/category', array('id' => $item['id'])).' "> ';
                echo $item['name'];
                echo '</a>';
                $this->showCategories($categories, $item['id']);
                echo '</li>';
            }
            echo '</ul>';
        }
    }

}