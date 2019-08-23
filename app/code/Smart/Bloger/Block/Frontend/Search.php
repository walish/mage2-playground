<?php


namespace Smart\Bloger\Block\Frontend;


class Search extends \Magento\Framework\View\Element\Template
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
    public function searchByKeyword()
    {
        $search=$this->request->getParam('key');
        $post=$this->_postFactory->create()->getCollection();
        //$product = $this->productFactory->create()->getCollection();
        $post->addFieldToFilter('post_name',['like'=>"%$search%"]);

        $post->addFieldToFilter('post_status', ['eq' => "1"]);
        return $post;
    }

}

?>