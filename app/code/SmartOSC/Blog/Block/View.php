<?php


namespace SmartOSC\Blog\Block;

use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use SmartOSC\Blog\Helper\Data;
use SmartOSC\Blog\Model\Blog;
use SmartOSC\Blog\Model\CategoryFactory;

/**
 * Class View
 * @package SmartOSC\Blog\Block
 */
class View extends Template
{

    /**
     * @var CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var mixed
     */
    protected $_blog;

    /**
     * @var Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @var Data
     */
    protected $_blogHelper;

    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var DateTime
     */
    protected $_date;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var ProductFactory
     */
    protected $_productCollection;

    /**
     * View constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Data $BlogHelper
     * @param ObjectManagerInterface $objectManager
     * @param DateTime $date
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Data $blogHelper,
        CollectionFactory $productCollectionFactory,
        ProductFactory $productCollection,
        ObjectManagerInterface $objectManager,
        DateTime $date,
        Data $helper,
        CategoryFactory $categoryFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_productCollection = $productCollection;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_coreRegistry = $registry;
        $this->_blogHelper = $blogHelper;
        $this->_objectManager = $objectManager;
        $this->_date = $date;
        $this->_helper = $helper;
        $this->_categoryFactory = $categoryFactory;
        $this->_blog = $this->getBlog();
    }

    /**
     * @return mixed
     */
    public function getBlog()
    {
        $blog = $this->_coreRegistry->registry('blogs_blog');
        $blog->setProduct($blog->getProduct());
        return $blog;
    }

    /**
     * @param $blog
     * @return \SmartOSC\Blog\Model\Category
     */
    public function getCategory($blog)
    {
        $catIds = $blog->getCategories();
        if ($catIds) {
            foreach ($catIds as $catId) {
                $cat = $this->_categoryFactory->create()->load($catId);
                if ($cat->getId()) {
                    return $cat;
                }
            }
        }
    }

    /**
     * @param $date
     * @return false|string
     */
    public function getRegisDeadline($date)
    {
        return date("D, M d", strtotime($date));
    }

    /**
     * @param $date
     * @return false|string
     */
    public function getDateFormat($date)
    {
        return date("M d", strtotime($date));
    }

    /**
     * @param $startTime
     * @return false|string
     */
    public function getStartTime($startTime)
    {
        return date("h:i A", strtotime($startTime));
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [Blog::CACHE_TAG . '_' . 'view'];
    }

    /**
     * @return Template
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function _prepareLayout()
    {
        $blog = $this->getBlog();
        $this->_addBreadcrumbs($blog);

        return parent::_prepareLayout();
    }

    /**
     * @param Blog $blog
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function _addBreadcrumbs(Blog $blog)
    {
        if ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'blogs',
                [
                    'label' => __('Blogs'),
                    'title' => __('Go to Blogs Page'),
                    'link' => $this->getUrl('blogs')
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'blog',
                [
                    'label' => $blog->getBlogName(),
                    'title' => $blog->getBlogName()
                ]
            );
        }
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getScopeConfig($path)
    {
        return $this->_scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getPriceWithCurrency()
    {
        return $this->_objectManager->get('Magento\Framework\Pricing\Helper\Data')->currency(number_format($this->_blog->getProduct()->getPrice(),
            2), true, false);
    }

    /**
     * @param $id
     * @return \Magento\Catalog\Model\Product
     */
    public function getProductCollection($id)
    {
        return $this->_productCollection->create()->load($id);
    }

    /**
     * @return string
     */
    public function getFavoriteImageSrc()
    {
        if ($this->isFavorited()) {
            return $this->getViewFileUrl('SmartOSC_Blog::images/heart-red.png');
        } else {
            return $this->getViewFileUrl('SmartOSC_Blog::images/heart-white.png');
        }
    }

    /**
     * @return bool
     */
    public function isFavorited()
    {
        $customerId = $this->getCustomerId();
        if (empty($customerId)) {
            return false;
        }

        $favCustomerIds = $this->_blog->getFavoritedCustomerIds();
        if (count($favCustomerIds) > 0 && in_array($customerId, $favCustomerIds)) {
            return true;
        }
        return false;
    }

    /**
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->_blogHelper->getCustomerId();
    }

    /**
     * @return string
     */
    public function getAvatarUrl()
    {
        return $avatarUrl = $this->_blog->getAvatarUrl();
    }

    /**
     * @return string
     */
    public function getQuoteBannerUrl()
    {
        $quoteBannerUrl = $this->_blog->getQuoteBannerUrl();
        return $quoteBannerUrl;
    }
}