<?php


namespace Smart\Bloger\Controller\Adminhtml\Product;


class Delete extends \Smart\Bloger\Controller\Adminhtml\Product
{
    protected $resultPageFactory;
    private $productFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Smart\Bloger\Model\ProductFactory $productFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
        $this->productFactory = $productFactory;
    }
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('product_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->productFactory->create();
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Product.'));
                // go to gridac
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['product_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Product to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}