<?php


namespace Smart\Bloger\Block\Frontend;


class Index extends \Magento\Framework\View\Element\Template
{
    protected $_postFactory;
    protected $catpostFactory;
    protected $categoryFactory;
    protected $postProductFactory;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Smart\Bloger\Model\CatPostFactory $catpostFactory,
        \Smart\Bloger\Model\CategoryFactory $categoryFactory,
        \Smart\Bloger\Model\PostFactory $postFactory,
        \Smart\Bloger\Model\PostProductFactory $postProductFactory
    )
    {
        $this->_postFactory = $postFactory;
        $this->catpostFactory = $catpostFactory;
        $this->categoryFactory = $categoryFactory;
        $this->postProductFactory = $postProductFactory;
        parent::__construct($context);
    }

    public function sayHello()
    {
        return __('<h1>Hello tú</h1>');
    }

    public function getPostCollection()
    {
        $post = $this->_postFactory->create();
        $collectionpost = $post->getCollection();

//        //insert data--------------------------------------------------------------
//        $post->setName('tutu');
//        $post->setUrlKey('key key');
//        $post->setTags('hello');
//        $post->setPostContent('toi la tu hahahhahaha');
//        $post->save();

        return $collectionpost;
    }
    public function getPostCastCollection()
    {
        $catpost= $this->catpostFactory->create();
        $collectioncatpost = $catpost->getCollection();
        return $collectioncatpost;
    }
    public function getCatCollection()
    {
        $cat= $this->categoryFactory->create();
        $collectioncat = $cat->getCollection();
        return $collectioncat;
    }

    public function fillter()
    {
        //loc ket qua
        $post = $this->_postFactory->create();
        $collection = $post->getCollection();
        $this->request->getParams();
        $id=$this->request->getParam('id');
        $collection->addFieldToFilter('category_id', ['eq' => "$id"]);
        return $collection;
    }

    public function getNews()
    {
        //get values of current page
        $page=($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        //get values of current limit
        $pageSize=($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 5;

        $postCollection = $this->_postFactory->create()->getCollection();
        $postCollection->addFieldToFilter('post_status', ['eq' => "1"]);
        //$postCollection->addFieldToFilter('post_id',1);
        //$postCollection->setOrder('title','ASC');

        $postCollection->setPageSize($pageSize);
        $postCollection->setCurPage($page);
        return $postCollection;
    }
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
//        $this->pageConfig->getTitle()->set(__('List Post'));


        if ($this->getNews()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager'

            )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(
                $this->getNews()
            );
            $this->setChild('pager', $pager);
            $this->getNews()->load();
        }
        return $this;
    }
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function showCategories($categories, $parent_id = 0, $char = '')
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

                echo '<li>'. '<a href=" '.$this->getUrl('*/*/listpost', array('catid' => $item['id'])).' "> ';
                echo $item['name'];
                echo '</a>';
                $this->showCategories($categories, $item['id'], $char . '|---');
                echo '</li>';
            }
            echo '</ul>';
        }
    }

}