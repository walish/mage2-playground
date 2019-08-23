<?php


namespace Train\Blog\Controller\Adminhtml\Category;


class Edit extends \Train\Blog\Controller\Adminhtml\Category
{
    protected $_coreRegistry = null;
    protected $resultPageFactory;
    private $categoryFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Train\Blog\Model\CategoryFactory $categoryFactory
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
        $this->_coreRegistry->register('train_blog_category', $model);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Category'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Category %1', $model->getId()) : __('New Category'));
        return $resultPage;
    }


}