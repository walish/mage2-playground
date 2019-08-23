<?php


namespace Train\Blog\Controller\Adminhtml;


abstract class Product extends \Magento\Backend\App\Action
{
    protected $_coreRegistry;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }
    public function initPage($resultPage)
    {
        return $resultPage;
    }
}