<?php


namespace SmartOSC\Blog\Controller\Adminhtml\Category;


use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use SmartOSC\Blog\Model\CategoryFactory;

/**
 * Class Delete
 * @package SmartOSC\Blog\Controller\Adminhtml\Category
 */
class Delete extends Action
{

    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(
        Action\Context $context,
        CategoryFactory $categoryFactory)
    {
        $this->_categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    /**
     * @return Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('category_id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_categoryFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The category has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['category_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a category to delete.'));
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SmartOSC_Blog::delete');
    }
}