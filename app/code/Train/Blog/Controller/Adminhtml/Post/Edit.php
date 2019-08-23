<?php


namespace Train\Blog\Controller\Adminhtml\Post;


class Edit extends \Train\Blog\Controller\Adminhtml\Post
{
    protected $_coreRegistry = null;
    protected $resultPageFactory;
    private $postFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Train\Blog\Model\PostFactory $postFactory
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
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Post'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Post %1', $model->getId()) : __('New Post'));
        return $resultPage;
    }
}