<?php

namespace SmartOSC\Blog\Block;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Blogs
 * @package SmartOSC\Blog\Block
 */
class Blogs extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @var \SmartOSC\Blog\Model\BlogFactory
     */
    protected $_blogFactory;

    /**
     * @var \SmartOSC\Blog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var
     */
    protected $_blogHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var
     */
    protected $_blogs;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \SmartOSC\Blog\Helper\Data
     */
    protected $_helper;

    /**
     * Blogs constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \SmartOSC\Blog\Model\BlogFactory $blogFactory
     * @param \SmartOSC\Blog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Framework\Registry $registry
     * @param \SmartOSC\Blog\Helper\Data $blogsHelper
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \SmartOSC\Blog\Model\BlogFactory $blogFactory,
        \SmartOSC\Blog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\Registry $registry,
        \SmartOSC\Blog\Helper\Data $blogsHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_objectManager = $objectManager;
        $this->_blogFactory = $blogFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_coreRegistry = $registry;
        $this->_blogsHelper = $blogsHelper;
        $this->_date = $date;
        $this->_blogs = $this->getBlogs();
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getBlogs()
    {
            $storeIds = [0, $this->getCurrentStoreId()];
        $collection = $this->_blogFactory->create()->getCollection()
            ->addFieldToFilter('status', 1)
            ->setOrder('start_time', 'ASC');
        $blogSearch = $this->getBlogSearch();
        if ($blogSearch) {
            $collection->setBlogNameFilter($blogSearch);
        }
        return $collection;
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCurrentStoreId()
    {
        return $this->_storeManager->getStore(true)->getId();
    }

    /**
     * @return mixed
     */
    public function getFilterCatId()
    {
        return $this->_coreRegistry->registry('filter_cat_id');
    }

    /**
     * @return mixed
     */
    public function getBlogSearch()
    {
        return $this->_coreRegistry->registry('blog_search');
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [\SmartOSC\Blog\Model\Blog::CACHE_TAG . '_' . 'list'];
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getPagedBlogs()
    {
        return $this->_blogs;
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return false|string
     */
    public function getBlogJson()
    {
        $collection = $this->_blogs;
        $results = [];
        if (count($collection)) {
            foreach ($collection as $blog) {
                $item = [
                    'id' => $blog->getId(),
                    'title' => $blog->getBlogName(),
                    'url' => $blog->getBlogUrl(),
                    'avatar_url' => $this->getAvatarUrl($blog),
                    'shortcontent' => $this->getShortContent($blog),
                ];
                $results[] = $item;
            }
        }

        return json_encode($results);
    }

    /**
     * @param $blog
     * @return mixed
     */
    public function getAvatarUrl($blog)
    {
        $avatarUrl = $blog->getAvatarUrl();
        return $avatarUrl;
    }

    /**
     * @param $time
     * @return mixed
     */
    public function convertTimeToLocal($time)
    {
        return $time->format('Y-m-d H:i:s');
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
     * @param $time
     * @return false|string
     */
    public function convertTimeToLocalGrid($time)
    {
        $date = date('Y-m-d H:i:s', $this->_date->timestamp($time) + $this->_date->getGmtOffset());
        return $date;
    }

    /**
     * @param $time
     * @return false|string
     */
    public function getFormattedTime($time)
    {
        $timestamp = $this->_date->timestamp($time);
        return date('M d, Y g:i A', $timestamp);
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBlogCategories()
    {
        $storeIds = [0, $this->getCurrentStoreId()];
        $collection = $this->_categoryFactory->create()->getCollection()
            ->addFieldToFilter('status', 1)
            ->setStoreFilter($storeIds);
        return $collection;
    }

    /**
     * @return $this|\Magento\Framework\View\Element\Template
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager', 'blogs.blog.index.pager')
            ->setAvailableLimit([5 => 5, 10 => 10, 20 => 20, 50 => 50, 100 => 100])
            ->setCollection($this->_blogs);
        $this->setChild('pager', $pager);
        $this->_blogs->load();
        return $this;
    }
}
