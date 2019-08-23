<?php


namespace Train\Blog\Block\Frontend\Content;

class CategoryView extends \Magento\Framework\View\Element\Template
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
    public function Filter($categoryId){
        $paramCategory = $this->getRequest()->getParam('id');
        if($paramCategory != ''){
            $category = explode(',',$categoryId);
            foreach ($category as $item){
                if ($item == $paramCategory){
                    return true;
                }
            }
        }else{
            return true;
        }
    }

}
