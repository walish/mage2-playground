<?php

namespace SmartOSC\Blog\Block\Sidebar;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use SmartOSC\Blog\Model\Category;
use SmartOSC\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

/**
 * Class CategoryTree
 * @package SmartOSC\Blog\Block\Sidebar
 */
class CategoryTree extends Template
{
    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var Category
     */
    protected $_category;
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var Context
     */
    protected $context;

    /**
     * @param CategoryCollectionFactory $postCollectionFactory
     * @param Registry $registry
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Category $category,
        CategoryCollectionFactory $postCollectionFactory,
        Registry $registry,
        Context $context,
        array $data = []
    ) {
        $this->categoryCollectionFactory = $postCollectionFactory;
        $this->_category = $category;
        $this->registry = $registry;
        $this->context = $context;

        parent::__construct($context, $data);
    }

    /**
     * @return Category[]
     */
    public function getTree()
    {
        return $this->categoryCollectionFactory->create()
            ->addAttributeToSelect(['category_name', 'url_key'])
            ->addVisibilityFilter()
            ->excludeRoot()
            ->getTree();
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function testgetTree()
    {
        $options[] = ['label' => '', 'value' => ''];
        $categoryCollection = $this->_category->getCollection()->addFieldToSelect('category_id')
            ->addFieldToSelect('category_name')->addFieldToSelect('parent_id');
        return $categoryCollection;
    }

    /**
     * @return Category|false
     */
    public function getCurrentCategory()
    {
        return $this->registry->registry('current_blog_category');
    }

    /**
     * @param \Mirasvit\Blog\Model\Category $category
     * @return bool
     */
    public function isCurrent($category)
    {
        if ($this->getCurrentCategory() && $this->getCurrentCategory()->getId() == $category->getId()) {
            return true;
        }

        return false;
    }
}
