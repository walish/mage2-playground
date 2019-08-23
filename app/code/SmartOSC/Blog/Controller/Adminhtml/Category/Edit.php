<?php

namespace SmartOSC\Blog\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use SmartOSC\Blog\Model\CategoryFactory;

/**
 * Class Edit
 * @package SmartOSC\Blog\Controller\Adminhtml\Category
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
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        Registry $registry,
        CategoryFactory $categoryFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    /**
     * @return Page|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('category_id');
        $model = $this->_categoryFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This category no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('blogs_category', $model);

        /** @var Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Blog Category') : __('New Blog Category'),
            $id ? __('Edit Blog Category') : __('New Blog Category')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Blog Category'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? __('Edit Category ') . $model->getCategoryTitle() : __('New Category'));

        return $resultPage;
    }

    /**
     * @return Page
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('SmartOSC_Blog::manage_categories')
            ->addBreadcrumb(__('Blog Categories'), __('Blog Categories'))
            ->addBreadcrumb(__('Manage Blog Categories'), __('Manage Blog Categories'));
        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SmartOSC_Blog::save');
    }
}
