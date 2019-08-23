<?php

namespace Train\Blog\Block\Frontend\Content;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;

class ListPost extends \Magento\Framework\View\Element\Template
{
    protected $postFactory;
    protected $collectionFactory;
    protected $categoryFactory;
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
        $collection = $this->collectionFactory->create();
        return $collection;
    }
    public function Seach()
    {
        $keyword = $this->getRequest()->getParam('keyword');
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('post_name', array('like' => '%'.$keyword.'%'));
        return $collection;
    }

    public function Filter($categoryId){
        $paramCategory = $this->getRequest()->getParam('id');
        $this->setCurrentCategory($paramCategory);

        if($paramCategory != ''){
            $a = explode(',',$categoryId);
            foreach ($a as $a){
                if ($a == $paramCategory){
                    return true;
                }
            }
        }
        return true;
    }
    public function getPage()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') :5;
        $collection = $this->getPostCollection();
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        return $collection;
    }

    protected function _prepareLayout()
    {
        if ($this->getPage()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager'
            )->setAvailableLimit(array(5 => 5, 10 => 10, 15 => 15))->setShowPerPage(true)->setCollection(
                $this->getPage()
            );
            $this->setChild('pager', $pager);
            $this->getPage()->load();
        }
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}