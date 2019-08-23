<?php


namespace Train\Blog\Controller\Adminhtml\Product;


class Edit extends \Train\Blog\Controller\Adminhtml\Product
{
    protected $_coreRegistry = null;
    protected $resultPageFactory;
    private $productFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Train\Blog\Model\ProductFactory $productFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
        $this->productFactory = $productFactory;
    }

    public function execute()
    {
        $id=$this->getRequest()->getParam('product_id');
        $model=$this->productFactory->create();
        if($id){
            $model->load($id);
            if(!$model->getId())
            {
                $this->messagemanager->addErrorMessage(__('This Product no longer exists.'));
                $resultRedirect=$this->resultPageFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('train_blog_product', $model);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Product'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Product %1', $model->getId()) : __('New Product'));
        return $resultPage;
    }
}