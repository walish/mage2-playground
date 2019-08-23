<?php


namespace Smart\Bloger\Controller\Adminhtml;


abstract class Post extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Smart_Bloger::Post';
    protected $_coreRegistry;
    protected $postRepository;
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
            ->addBreadcrumb(__('Post'), __('Post'));
        return $resultPage;
    }
}