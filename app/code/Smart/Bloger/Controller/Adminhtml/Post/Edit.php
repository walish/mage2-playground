<?php


namespace Smart\Bloger\Controller\Adminhtml\Post;


class Edit extends \Smart\Bloger\Controller\Adminhtml\Category
{
    protected $resultPageFactory;
    private $postFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Smart\Bloger\Model\PostFactory $postFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
        $this->postFactory = $postFactory;
    }
    public function execute()
    {
        $id=$this->getRequest()->getParam('post_id');
        $model=$this->postFactory->create();
        if($id){
            $model->load($id);
            if(!$model->getId())
            {
                $this->messagemanager->addErrorMessage(__('This Post no longer exists.'));
                $resultRedirect=$this->resultPageFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('smart_bloger_post', $model);
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Post') : __('New Post'),
            $id ? __('Edit Post') : __('New Post')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Category'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Post %1', $model->getId()) : __('New Post'));
        return $resultPage;
    }
}