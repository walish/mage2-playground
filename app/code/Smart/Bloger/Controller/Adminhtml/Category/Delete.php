<?php


namespace Smart\Bloger\Controller\Adminhtml\Category;


class Delete extends \Smart\Bloger\Controller\Adminhtml\Category
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
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('category_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->categoryFactory->create();
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Category.'));
                // go to gridac
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['category_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Category to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}