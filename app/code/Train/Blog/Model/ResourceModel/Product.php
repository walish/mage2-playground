<?php


namespace Train\Blog\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Model\AbstractModel;
class Product extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function __construct(
        EntityManager $entityManager,
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        $this->entityManager = $entityManager;
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('train_blog_product', 'product_id');
    }
}