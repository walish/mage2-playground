<?php


namespace SmartOSC\Blog\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;

/**
 * Class Index
 * @package SmartOSC\Blog\Controller\Adminhtml\Blog
 */
class Index extends Action
{

    /**
     *
     */
    const ADMIN_RESOURCE = 'SmartOSC_Blog::manage_blogs';


    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('SmartOSC_Blog::manage_blogs');
        $resultPage->addBreadcrumb(__('Blogs'), __('Blogs'));
        $resultPage->addBreadcrumb(__('Manage Blogs'), __('Manage Blogs'));
        $resultPage->getConfig()->getTitle()->prepend(__('Blogs'));

        return $resultPage;
    }
}