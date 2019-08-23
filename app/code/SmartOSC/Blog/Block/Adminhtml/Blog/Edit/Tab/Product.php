<?php


namespace SmartOSC\Blog\Block\Adminhtml\Blog\Edit\Tab;


use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Helper\Data;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\LinkFactory;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Directory\Model\Currency;
use Magento\Framework\Registry;
use Magento\Store\Model\ScopeInterface;
use SmartOSC\Blog\Model\BlogFactory;

/**
 * Class Product
 * @package SmartOSC\Blog\Block\Adminhtml\Blog\Edit\Tab
 */
class Product extends Extended
{

    /**
     * @var Registry|null
     */
    protected $_coreRegistry = NULL;

    /**
     * @var BlogFactory
     */
    protected $_blogFactory;

    /**
     * @var LinkFactory
     */
    protected $_linkFactory;

    /**
     * @var Status
     */
    protected $_productStatus;

    /**
     * @var Visibility
     */
    protected $_productVisibility;

    /**
     * Product constructor.
     * @param Context $context
     * @param Data $backendHelper
     * @param Registry $coreRegistry
     * @param BlogFactory $blogFactory
     * @param LinkFactory $linkFactory
     * @param Status $productStatus
     * @param Visibility $productVisibility
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $backendHelper,
        Registry $coreRegistry,
        BlogFactory $blogFactory,
        LinkFactory $linkFactory,
        Status $productStatus,
        Visibility $productVisibility,

        array $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_blogFactory = $blogFactory;
        $this->_linkFactory = $linkFactory;
        $this->_productStatus = $productStatus;
        $this->_productVisibility = $productVisibility;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return mixed|string
     */
    public function getGridUrl()
    {
        return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/productgrid',
            ['_current' => true]);
    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blogs_product_grid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
//        if ($this->getBlog() && $this->getBlog()->getId()) {
//            $this->setDefaultFilter(['in_products' => 1]);
//        }
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
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('blog_id', ['in' => $productIds]);
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('blog_id', ['nin' => $productIds]);
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
    protected function _getSelectedProducts()
    {
        $products = $this->getSelectedProducts();
        if (!is_array($this->getSelectedProducts())) {
            return $products = array_keys($this->getAllowedSelectedProducts());
        }
        return $products;
    }

    /**
     * @return array
     */
    public function getAllowedSelectedProducts()
    {
        $blogId = $this->getRequest()->getParam('blog_id');
        $blog = $this->_blogFactory->create()->load($blogId);
        $products = $blog->getSelectedProducts();

        if (!$products) {
            return [];
        }

        $productIds = [];

        foreach ($products as $productId) {
            $productIds[$productId] = ['id' => $productId];
        };
        return $productIds;
    }

    /**
     * @return Extended
     */
    protected function _prepareCollection()
    {
        $collection = $this->_linkFactory->create()->getProductCollection();
        $this->setCollection($collection);

//        $blogId = $this->getRequest()->getParam('blog_id');
//        $blog = $this->_blogFactory->create()->load($blogId);
//
//        if ($blog->getId() && $blog->getProductId()) {
//            $productId = $blog->getProductId();
//            $collection->addFieldToFilter('entity_id', $productId);
//        } else {
//            $associatedProductIds = [];
//            $blogs = $this->_blogFactory->create()->getCollection();
//            foreach ($blogs as $blog) {
//                $associatedProductIds[] = $blog->getProductId();
//            }
//
//            //Get id of products that have type 'release' and haven't associated with any event
//            $blogProductIds = $this->getBlogProductIds();
//            foreach ($blogProductIds as $key => $id) {
//                if (in_array($id, $associatedProductIds)) {
//                    unset($blogProductIds[$key]);
//                }
//            }
//
//            $collection->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()])
//                ->addFieldToFilter('entity_id', ['in' => $blogProductIds]);
//            $collection->getSelect()->distinct(true)->join(
//                ['stock_table' => $collection->getTable('cataloginventory_stock_status')],
//                'e.entity_id = stock_table.product_id',
//                []);
//            $collection->getSelect()->where('stock_table.stock_status = 1');
//        }
//        $this->setCollection($collection);

        return parent::_prepareCollection();
    }


    /**
     * @return array
     */
    public function getBlogProductIds()
    {
        $blogProductIds = [];
        $productCollection = $this->_linkFactory->create()->getProductCollection()
            ->addAttributeToFilter('status', ['in' => $this->_productStatus->getVisibleStatusIds()]);
        foreach ($productCollection as $product) {
            $blogProductIds[] = $product->getId();
        }
        return $blogProductIds;
    }

    /**
     * @return Extended
     * @throws \Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_products',
            [
                'type' => 'checkbox',
                'html_name' => 'in_products',
                'values' => $this->_getSelectedProducts(),
                'align' => 'center',
                'index' => 'entity_id',
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select'
            ]
        );

        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

//        $this->addColumn(
//            'product_thumbnail',
//            [
//                'header' => __('Thumbnail'),
//                'align' => 'left',
//                'width' => '97',
//                'renderer' => 'SmartOSC\Blog\Block\Adminhtml\Grid\Column\Renderer\Thumbnail'
//            ]
//        );

        $this->addColumn(
            'product_name',
            [
                'header' => __('Product Name'),
                'index' => 'name',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ]
        );
        $this->addColumn(
            'product_sku',
            [
                'header' => __('SKU'),
                'index' => 'sku',
                'header_css_class' => 'col-sku',
                'column_css_class' => 'col-sku'
            ]
        );
        $this->addColumn(
            'product_quantity',
            [
                'header' => __('Quantity'),
                'index' => 'stock_qty',
                'header_css_class' => 'col-quantity',
                'column_css_class' => 'col-quantity'
            ]
        );
        $this->addColumn(
            'product_price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'currency_code' => (string)$this->_scopeConfig->getValue(
                    Currency::XML_PATH_CURRENCY_BASE,
                    ScopeInterface::SCOPE_STORE
                ),
                'index' => 'price',
                'header_css_class' => 'col-price',
                'column_css_class' => 'col-price'
            ]
        );

        return parent::_prepareColumns();
    }


}