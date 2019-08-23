<?php


namespace Smart\Bloger\Block\Frontend;


class Productdetail extends \Magento\Framework\View\Element\Template
{
    protected $_postFactory;
    protected $catpostFactory;
    protected $productFactory;
    protected $categoryFactory;
    protected $postProductFactory;
    protected $request;
    protected $cart;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Smart\Bloger\Model\CatPostFactory $catpostFactory,
        \Smart\Bloger\Model\CategoryFactory $categoryFactory,
        \Smart\Bloger\Model\PostFactory $postFactory,
        \Smart\Bloger\Model\PostProductFactory $postProductFactory,
        \Smart\Bloger\Model\ProductFactory $productFactory,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Checkout\Model\Cart $cart
    )
    {
        $this->_postFactory = $postFactory;
        $this->cart = $cart;
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
    public function getProduct()
    {
        $productid=$this->request->getParam('productid');
        $product = $this->productFactory->create()->getCollection();
        $product->addFieldToFilter('product_id',['eq'=>$productid]);
        $product->addFieldToFilter('product_status', ['eq' => "1"]);

        return $product;
    }
    public function execute()
    {
        $productId =$this->request->getParam('productid');
        $qty=$this->request->getParam('qty');
        $params = array(
            'form_key' => $this->formKey->getFormKey(),
            'product' => $productId, //product Id
            'qty'   =>$qty //quantity of product
        );
        //Load the product based on productID
        $product = $this->productFactory->load($productId);
        $this->cart->addProduct($product, $params);
        $this->cart->save();
    }
}