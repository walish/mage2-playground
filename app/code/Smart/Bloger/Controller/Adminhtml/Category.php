<?php


namespace Smart\Bloger\Controller\Adminhtml;


abstract class Category extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Smart_Bloger::bloger';
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
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Smart'), __('Smart'))
            ->addBreadcrumb(__('Category'), __('Category'));
        return $resultPage;
    }
}