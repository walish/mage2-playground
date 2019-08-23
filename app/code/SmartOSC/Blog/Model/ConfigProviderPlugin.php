<?php


namespace SmartOSC\Blog\Model;


use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

class ConfigProviderPlugin implements ConfigProviderInterface
{

    protected $checkoutSession;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;

    /**
     * @param CheckoutSession $checkoutSession
     * @param \Magento\Checkout\Model\Cart $cart
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        \Magento\Checkout\Model\Cart $cart
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->cart = $cart;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $result = [];

        $result['quoteData']['isBlogs'] = $this->getIsBlogs();
        return $result;
    }

    /**
     * @return bool
     */
    public function getIsBlogs()
    {
        $isBlogs = true;
        $countItems = 0;
        foreach ($this->cart->getQuote()->getItemsCollection() as $_item) {
            /* @var $_item \Magento\Quote\Model\Quote\Item */
            if ($_item->isDeleted() || $_item->getParentItemId()) {
                continue;
            }
            $countItems++;
            if ($_item->getProduct()->getTypeId() != 'simple') {
                $isBlogs = false;
                break;
            }
        }
        return $countItems == 0 ? false : $isBlogs;
    }
}