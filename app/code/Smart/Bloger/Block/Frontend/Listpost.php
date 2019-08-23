<?php


namespace Smart\Bloger\Block\Frontend;


class Listpost extends \Magento\Framework\View\Element\Template
{
    protected $_postFactory;
    protected $catpostFactory;
    protected $productFactory;
    protected $categoryFactory;
    protected $postProductFactory;
    protected $request;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Smart\Bloger\Model\CatPostFactory $catpostFactory,
        \Smart\Bloger\Model\CategoryFactory $categoryFactory,
        \Smart\Bloger\Model\PostFactory $postFactory,
        \Smart\Bloger\Model\PostProductFactory $postProductFactory,
        \Smart\Bloger\Model\ProductFactory $productFactory,
        \Magento\Framework\App\RequestInterface $request
    )
    {
        $this->_postFactory = $postFactory;
        $this->request = $request;
        $this->catpostFactory = $catpostFactory;
        $this->productFactory = $productFactory;
        $this->categoryFactory = $categoryFactory;
        $this->postProductFactory = $postProductFactory;
        parent::__construct($context);
    }

    public function sayHello()
    {
        return __('<h1>Hello tú</h1>');
    }
    public function getPostByIdCategory()
    {
        $page=($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $pageSize=($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 5;


        $catid=$this->request->getParam('catid');
        $catpost = $this->catpostFactory->create()->getCollection();

        $catpost->addFieldToFilter('category_id',['eq' => "$catid"]);
//        echo '<h3/><b><---'.$catid.'---></b></h3>';
        $arrcatpost=[];
        foreach ($catpost as $key=>$post)
        {
            array_push($arrcatpost, $post->getPostId());
        }

//        echo '<pre/>';
        $catpostnew=array_unique($arrcatpost, 0);
//        print_r($catpostnew);die;


        $post=$this->_postFactory->create()->getCollection();
        $post->addFieldToFilter('post_id',['in'=>$catpostnew]);
        $post->addFieldToFilter('post_status', ['eq' => "1"]);


        $post->setPageSize($pageSize);
        $post->setCurPage($page);

        return $post;
    }

//    public function getNews()
//    {
//        //get values of current page
//        $page=($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
//        //get values of current limit
//        $pageSize=($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 5;
//
//
//        $postCollection = $this->_postFactory->create()->getCollection();
//
//        //$postCollection->addFieldToFilter('post_id',1);
//        //$postCollection->setOrder('title','ASC');
//
//
//        $postCollection->setPageSize($pageSize);
//        $postCollection->setCurPage($page);
//        return $postCollection;
//    }
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
//        $this->pageConfig->getTitle()->set(__('List Post'));


        if ($this->getPostByIdCategory()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager'

            )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(
                $this->getPostByIdCategory()
            );
            $this->setChild('pager', $pager);
            $this->getPostByIdCategory()->load();
        }
        return $this;
    }
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}