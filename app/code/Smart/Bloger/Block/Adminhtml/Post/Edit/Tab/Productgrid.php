<?php


namespace Smart\Bloger\Block\Adminhtml\Post\Edit\Tab;


class Productgrid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    protected $_coreRegistry = null;

    protected $_productFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        //\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory,
        \Smart\Bloger\Model\ProductFactory $productFactory,
        \Smart\Bloger\Model\PostFactory $postFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_productFactory = $productFactory;
        $this->postFactory = $postFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }
    protected function _construct()
    {
        parent::_construct();
        $this->setId('product_grid');
        $this->setDefaultSort('product_id');
        $this->setUseAjax(true);

    }
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('product_id', ['in' => $productIds]);
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('product_id', ['nin' => $productIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
    protected function _prepareCollection()
    {

        echo 'asd';
        $collection = $this->_productFactory->create()->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_products',
            [
                'type' => 'checkbox',
                'html_name' => 'products_id',
                'required'  => true,
                'values' => $this->_getSelectedProducts(),
                'align' => 'center',
                'index' => 'product_id',
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select'
            ]
        );
        $this->addColumn(
            'name',
            [
                'header' => __('Name'),
                'index' => 'product_name',
                'header_css_class' => 'col-name',
                'column_css_class' => 'col-name'
            ]
        );

        return parent::_prepareColumns();
    }


    public function getGridUrl()
    {
        return $this->getUrl(
            'smart_bloger/*/index',
            ['_current' => true]
        );
    }


    protected function _getSelectedProducts()
    {

        $products = array_keys($this->getSelectedProducts());

        return $products;
    }


    public function getSelectedProducts()
    {
        $tm_id = $this->getRequest()->getParam('id');
        if(!isset($tm_id)) {
            $tm_id = 0;
        }

        // if you save product id in your custom table

        $collection = $this->postFactory->create()->load($tm_id);
        $data =  $collection->getProductId();
        $products = explode(',',$data);

        $proIds = array();

        foreach($products as $product) {
            $proIds[$product] = array('id'=>$product);
        }

        return $proIds;
    }

}