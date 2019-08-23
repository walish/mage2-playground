<?php

namespace SmartOSC\Blog\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package SmartOSC\Blog\Controller\Index
 */
class Index extends Action
{
    /**
     * @var Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute()
    {
        $blogSearch = $this->getRequest()->getPost('blog', false);
        if ($blogSearch) {
            $this->_coreRegistry->register('blog_search', trim($blogSearch));
        }
        $resultPage = $this->resultPageFactory->create();
        $pageTitle = 'SmartOSC Blog';

        $resultPage->getConfig()->getTitle()->set($pageTitle);

        return $resultPage;
    }
}
