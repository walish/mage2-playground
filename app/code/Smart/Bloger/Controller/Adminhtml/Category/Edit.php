<?php


namespace Smart\Bloger\Controller\Adminhtml\Category;


class Edit extends \Smart\Bloger\Controller\Adminhtml\Category
{
    protected $resultPageFactory;
    private $categoryFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Smart\Bloger\Model\CategoryFactory $categoryFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
        $this->categoryFactory = $categoryFactory;
    }
    public function execute()
    {
        $id=$this->getRequest()->getParam('category_id');
        $model=$this->categoryFactory->create();
        if($id){
            $model->load($id);
            if(!$model->getId())
            {
                $this->messagemanager->addErrorMessage(__('This Category no longer exists.'));
                $resultRedirect=$this->resultPageFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('smart_bloger_category', $model);
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Category') : __('New Category'),
            $id ? __('Edit Category') : __('New Category')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Category'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Category %1', $model->getId()) : __('New Category'));
        return $resultPage;
    }
}