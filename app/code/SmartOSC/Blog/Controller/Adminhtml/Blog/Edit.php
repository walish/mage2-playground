<?php

namespace SmartOSC\Blog\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Edit
 * @package SmartOSC\Blog\Controller\Adminhtml\Blog
 */
class Edit extends Action
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
     * Edit constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('blog_id');
        $blog = $this->_objectManager->create('SmartOSC\Blog\Model\Blog');

        if ($id) {
            $blog->load($id);
            if (!$blog->getId()) {
                $this->messageManager->addError(__('This blog no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $blog->setData($data);
        }

        $this->_coreRegistry->register('blogs_blog', $blog);

        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Blogs') : __('New Blogs'),
            $id ? __('Edit Blogs') : __('New Blogs')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Blogs'));
        $resultPage->getConfig()->getTitle()
            ->prepend($blog->getId() ? __('Edit Blog ') . $blog->getTitle() : __('New Blog'));

        return $resultPage;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('SmartOSC_Blog::manage_blogs')
            ->addBreadcrumb(__('Blogs'), __('Blogs'))
            ->addBreadcrumb(__('Manage Blogs'), __('Manage Blogs'));
        return $resultPage;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SmartOSC_Blog::save');
    }
}
