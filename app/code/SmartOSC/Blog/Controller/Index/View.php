<?php

namespace SmartOSC\Blog\Controller\Index;

use Magento\Framework\App\Action\Action;

/**
 * Class View
 * @package SmartOSC\Blog\Controller\Index
 */
class View extends Action
{
    /**
     * @var \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \SmartOSC\Blog\Model\BlogFactory
     */
    protected $_blogFactory;

    /**
     * View constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \SmartOSC\Blog\Model\BlogFactory $blogFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \SmartOSC\Blog\Model\BlogFactory $blogFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_blogFactory = $blogFactory;
        parent::__construct($context);
    }

    /**
     * @return bool|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $blogId = (int)$this->getRequest()->getParam('blog_id', false);
        if (!$blogId) {
            return false;
        }
        $blog = $this->_blogFactory->create()->load($blogId);

        if ($blog->getId()) {
            $this->_coreRegistry->register('blogs_blog', $blog);
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set($blog->getTitle());
            return $resultPage;
        } else {
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index');
        }
    }
}
