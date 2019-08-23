<?php


namespace Smart\Bloger\Model\ResourceModel;


class PostProduct extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('smart_bloger_post_product', 'postproduct_id');
    }

}