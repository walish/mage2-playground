<?php


namespace SmartOSC\Blog\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;

class Blog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected $_blogStoreTable;

    protected $_productBlogTable;

    protected $_productFactory;

    protected $_date;

    protected $_categoryBlogTable;
    protected $_helper;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \SmartOSC\Blog\Helper\Data $helper,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
        $this->_productFactory = $productFactory;
        $this->_helper = $helper;
    }

    protected function _construct()
    {
        $this->_init('smartosc_blog', 'blog_id');
        $this->_blogStoreTable = $this->getTable('smartosc_blog_store');
        $this->_categoryBlogTable = $this->getTable('smartosc_blog_categories');
        $this->_productBlogTable = $this->getTable('smartosc_blog_product');
    }

    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {

        parent::_afterLoad($object); // TODO: Change the autogenerated stub

        /**
         *  If create new blog
         */
        if (!$object->getId()) {
             $this;
        }

        if ($object->hasStartTime()) {
            $startTime = $this->_helper->convertTime($object->getStartTime(),true);
            $object->setStartTime($startTime);
        }
        if ($object->hasEndTime()) {
            $endTime = $this->_helper->convertTime($object->getEndTime(),true);
            $object->setEndTime($endTime);
        }

        if ($object->getId()) {
            // load event available in stores
            $object->setStores($this->getStoreIds((int)$object->getId()));
            // load categories associate to this event
            $object->setCategories($this->getCategoryIds((int)$object->getId()));
            // load product associate to this event
            $object->setSelectedProducts($this->getSelectedProducts((int)$object->getId()));
            $object->setProductId($this->getProductId((int)$object->getId()));
            $object->setProduct($this->getProduct((int)$object->getId()));
        }
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/yourFather.log');

        $logger = new \Zend\Log\Logger();

        $logger->addWriter($writer);

        $logger->info(print_r($this->getSelectedProducts($object->getId()), true));


        return $this;
    }

    public function getStoreIds($blogId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getTable($this->_blogStoreTable), 'store_id')
            ->where('blog_id = ?', $blogId);
        return $this->getConnection()->fetchCol($select);
    }

    public function getCategoryIds($blogId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getTable($this->_categoryBlogTable), 'category_id')
            ->where('blog_id = ?', $blogId);
        return $this->getConnection()->fetchCol($select);
    }

    public function getSelectedProducts($blogId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getTable($this->_productBlogTable),'entity_id')
            ->where('blog_id = ? ',$blogId);
        return $this->getConnection()->fetchCol($select);

    }
    public function getProductId($blogId)
    {
        $select = $this->getConnection()->select()->from(
            $this->getTable($this->_productBlogTable), 'entity_id')
            ->where('blog_id = ?', $blogId);
        return $this->getConnection()->fetchOne($select);
    }

    public function getProduct($blogId)
    {
        $productId = $this->getProductId($blogId);
        if ($productId) {
            return $this->_productFactory->create()->load($productId);
        } else {
            return null;
        }
    }

    protected function _beforeSave(AbstractModel $object)
    {
        if ($object->isObjectNew() && !$object->hasCreatedTime()) {
            $object->setCreatedTime($this->_date->gmtDate());
        }

        if ($object->hasData('stores') && !is_array($object->getStores())) {
            $object->setStores([$object->getStores()]);
        }

        return parent::_beforeSave($object);
    }

    protected function _afterSave(AbstractModel $object)
    {
        $connection = $this->getConnection();

        /**
         * Save event_stores
         */

        $stores = $object->getStores();
        if (!empty($stores)) {
            $condition = ['blog_id = ?' => $object->getId()];
            $connection->delete($this->_blogStoreTable, $condition);

            $insertedStoreIds = [];
            foreach ($stores as $storeId) {
                if (in_array($storeId, $insertedStoreIds)) {
                    continue;
                }

                $insertedStoreIds[] = $storeId;
                $storeInsert = ['store_id' => $storeId, 'blog_id' => $object->getId()];
                $connection->insert($this->_blogStoreTable, $storeInsert);
            }
        }
        /*
         * save event_categories
         */

        $categories = $object->getCategoryId();
        if (!($categories === null)) {

            $condition = ['blog_id = ?' => (int)$object->getId()];

            $connection->delete($this->_categoryBlogTable, $condition);

            $insertedCategoryIds = [];

            if ($categories) {
                    $insertedCategoryIds[] = $categories;
                    $categoryInsert = ['category_id' => $categories, 'blog_id' => (int)$object->getId()];
                    $connection->insert($this->_categoryBlogTable, $categoryInsert);

            }
        }

        $products = $object->getSelectedProducts();

        if (!($products === null)) {

            $condition = ['blog_id = ?' => (int)$object->getId()];

            $connection->delete($this->_productBlogTable, $condition);

            $insertedProductIds = [];

            if ($products) {

                foreach ($products as $productId) {

                    if (in_array($productId, $insertedProductIds)) {
                        continue;
                    }

                    $insertedProductIds[] = $productId;
                    $productInsert = ['entity_id' => $productId, 'blog_id' => (int)$object->getId()];
                    $connection->insert($this->_productBlogTable, $productInsert);
                }
            }
        }
        /*
         * save event_product
         */

//        $products = $object->getProducts();
//        $productId = $object->getProductAssociated();
//        if (!empty($productId)) {
//            $condition = ['blog_id = ?' => $object->getId()];
//            $connection->delete($this->_productBlogTable, $condition);
//
//            $productInsert = ['blog_id' => $productId, 'blog_id' => $object->getId()];
//            $connection->insert($this->_productBlogTable, $productInsert);
//        }
//
//        return $this;
    }
    public function getProducts($productId){
            $select = $this->getConnection()->select()->from(
                $this->getTable($this->_productBlogTable), 'entity_id')
                ->where('blog_id = ?', $productId);
            return $this->getConnection()->fetchCol($select);

    }
}