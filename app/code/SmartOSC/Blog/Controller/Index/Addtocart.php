<?php


namespace SmartOSC\Blog\Controller\Index;

use Magento\Catalog\Model\ProductFactory;
use Magento\Checkout\Model\Cart;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

/**
 * Class Addtocart
 * @package SmartOSC\Blog\Controller\Index
 */
class Addtocart extends Action
{

    /**
     * @var ProductFactory
     */
    protected $_productFactory;

    /**
     * @var Cart
     */
    protected $_cart;

    /**
     * Addtocart constructor.
     * @param Context $context
     * @param ProductFactory $productFactory
     * @param Cart $cart
     */
    public function __construct(
        Context $context,
        ProductFactory $productFactory,
        Cart $cart
    )
    {
        $this->_productFactory = $productFactory;
        $this->_cart = $cart;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $productId = $this->getRequest()->getParam('product');
        $formKey = $this->getRequest()->getParam('formkey');
        $params = array(
            'product' => $productId,
            'formkey' => $formKey,
            'qty' => 1
        );
        $product = $this->_productFactory->create()->load($productId);
        $this->_cart->addProduct($product, $params);
        $this->_cart->save();

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('checkout', ['_secure' => true]);
        // TODO: Implement execute() method.
    }
}