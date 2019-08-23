<?php


namespace SmartOSC\Blog\Model;


class Blog extends \Magento\Framework\Model\AbstractModel
{

    protected $_productFactory;

    protected $_eventPrefix = 'blogs';

    protected $_eventObject = 'blog';

    protected $urlModel;

    protected $_idFieldName = 'blog_id';

    protected $_blogsHelper;

    protected $_formKey;

    const CACHE_TAG = 'blogs_blog';

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\UrlFactory $urlFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \SmartOSC\Blog\Helper\Data $helper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->urlModel = $urlFactory->create();
        $this->_productFactory = $productFactory;
        $this->stockItem = $stockItem;
        $this->_blogsHelper = $helper;
        $this->_date = $date;
        $this->_formKey = $formKey;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function getEnableStatus() {
        return 1;
    }

    public function getDisableStatus() {
        return 0;
    }

    public function getProducts($productId = null)
    {
        if (!$productId) $productId = $this->getId();
        /** @var \SmartOSC\Blog\Model\ResourceModel\Blog $resource */
        $resource = $this->getResource();

        return $resource->getProducts($productId);
    }
    public function getProductId()
    {
        return $this->getResource()->getProductId($this->getId());
    }

    public function isAssociatedProduct()
    {
        $isAssociatedProduct = false;
        $productId = (int)$this->getProductId();
        if ($productId && $productId > 0) {
            $product = $this->_productFactory->create()->load($productId);
            if ($product->getId()) {
                $isAssociatedProduct = true;
            }
        }
        return $isAssociatedProduct;
    }

    public function getCategoryUrl()
    {
        return $this->urlModel->getUrl('blogs/index/view', ['blog_id' => $this->getId()]);
    }

    public function getBlogUrl()
    {
        return $this->urlModel->getUrl('blogs/index/view', ['blog_id' => $this->getId()]);
    }

    public function getAvailableStatuses() {
        return [$this->getDisableStatus() => __('Disabled'), $this->getEnableStatus() => __('Enabled')];
    }

    public function getAvatarUrl()
    {
        $avatarName = $this->getImage();
        if ($avatarName != '') {
            $avatarUrl = $this->_blogsHelper->getImageUrl($avatarName, 'smartosc/blogs/blog/image/');
        }
        // Else ?
        return $avatarUrl;
    }

    public function getQuoteBannerUrl()
    {
        $quoteBanner = $this->getBanner();
        if ($quoteBanner != '') {
            $quoteBannerUrl = $this->_blogsHelper->getImageUrl($quoteBanner, 'smartosc/blogs/blog/image/');
        }
        return $quoteBannerUrl;
    }

    public function getAddToCartUrl()
    {
        $productId = (int)$this->getProductId();
        return $this->urlModel->getUrl('blogs/index/addtocart',
            ['product' => $productId, 'formkey' => $this->_formKey->getFormKey()]);
    }

    public function getProductQty()
    {
        $product = $this->getProduct();
        if ($product && $productId = $product->getId()) {
            return $this->stockItem->getStockQty($productId, $product->getStore()->getWebsiteId());
        } else {
            return 0;
        }
    }

    public function getPrice()
    {
        $product = $this->getProduct();
        if ($product && $product->getId()) {
            return $product->getPrice();
        } else {
            return 0;
        }
    }

    protected function _construct()
    {
        $this->_init('SmartOSC\Blog\Model\ResourceModel\Blog');
    }
}