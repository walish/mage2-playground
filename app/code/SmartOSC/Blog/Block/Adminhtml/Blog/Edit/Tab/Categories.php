<?php

namespace SmartOSC\Blog\Block\Adminhtml\Blog\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Framework\Registry;
use SmartOSC\Blog\Model\BlogFactory;
use SmartOSC\Blog\Model\CategoryFactory;

/**
 * Class Categories
 * @package SmartOSC\Blog\Block\Adminhtml\Blog\Edit\Tab
 */
class Categories extends Extended
{
    /**
     * @var Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @var BlogFactory
     */
    protected $_blogFactory;

    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * Categories constructor.
     * @param Context $context
     * @param Data $backendHelper
     * @param BlogFactory $blogFactory
     * @param CategoryFactory $categoryFactory
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        BlogFactory $blogFactory,
        CategoryFactory $categoryFactory,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_blogFactory = $blogFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return mixed|string
     */
    public function getGridUrl()
    {
        return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl(
            '*/*/categorygrid',
            ['_current' => true]
        );
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blogs_category_grid');
        $this->setDefaultSort('category_id');
        $this->setUseAjax(true);
        if ($this->getBlog() && $this->getBlog()->getId()) {
            $this->setDefaultFilter(['in_categories' => 1]);
        }
    }

    /**
     * @return mixed
     */
    public function getBlog()
    {
        return $this->_coreRegistry->registry('blogs_blog');
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this|Extended
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_categories') {
            $categoryIds = $this->_getSelectedCategories();
            if (empty($categoryIds)) {
                $categoryIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('category_id', ['in' => $categoryIds]);
            } else {
                if ($categoryIds) {
                    $this->getCollection()->addFieldToFilter('category_id', ['nin' => $categoryIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * @return array
     */
    protected function _getSelectedCategories()
    {
        $categories = array_keys($this->getSelectedCategories());
        return $categories;
    }

    /**
     * @return array
     */
    public function getSelectedCategories()
    {
        $blogId = $this->getRequest()->getParam('blog_id');
        $blog = $this->_blogFactory->create()->load($blogId);
        $categories = $blog->getSelectedCategories();

        if (!$categories) {
            return [];
        }

        $categoryIds = [];

        foreach ($categories as $categoryId) {
            $categoryIds[$categoryId] = ['id' => $categoryId];
        }

        return $categoryIds;
    }

    /**
     * @return Extended
     */
    protected function _prepareCollection()
    {
        $collection = $this->_categoryFactory->create()->getCollection()
            ->addFieldToFilter('status', 1);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return Extended
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_categories',
            [
                'type' => 'checkbox',
                'name' => 'in_categories',
                'values' => $this->_getSelectedCategories(),
                'align' => 'center',
                'index' => 'category_id',
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select'
            ]
        );

        $this->addColumn(
            'category_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'type' => 'number',
                'index' => 'category_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'category_name',
            [
                'header' => __('Name'),
                'type' => 'text',
                'index' => 'category_name',
                'header_css_class' => 'col-title',
                'column_css_class' => 'col-title'
            ]
        );

        return parent::_prepareColumns();
    }
}
