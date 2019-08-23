<?php


namespace Smart\Bloger\Block\Frontend;


class Detail extends \Magento\Framework\View\Element\Template
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

    public function fillterDetailByIdPost()
    {
        $post = $this->_postFactory->create();
        $collection = $post->getCollection();
        $this->request->getParams();
        $id=$this->request->getParam('idpost');
        $collection->addFieldToFilter('post_id', ['eq' => "$id"]);
//        foreach ($collection as $key =>$value)
//        {
//            $post->setData('view_count',$value->getViewCount()+1);
//        }
      //  $postview=$this->_postFactory->create()->load($id);
//        $view = $postview->getData('view_count')+1;
     //   \Zend_Debug::dump($view);die('asd');
        return $collection;
    }
    public function getProductByIdPost()
    {
        $idpost=$this->request->getParam('idpost');
        $postproduct = $this->postProductFactory->create();
        $collectionpostpro = $postproduct->getCollection();

        $collectionpostpro->addFieldToFilter('post_id',['eq' => "$idpost"]);

        $arrpostpro=[];
        foreach ($collectionpostpro as $key=>$post)
        {
            array_push($arrpostpro, $post->getProductId());
            //echo '<br/>'. $post->getPostId().'--->'.$post->getProductId();
        }

        $product=$this->productFactory->create();
        $collectionproduct = $product->getCollection();

        $collectionproduct->addFieldToFilter('product_id',['in'=>$arrpostpro]);
        return $collectionproduct;

    }

}