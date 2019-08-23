<?php


namespace SmartOSC\Blog\Controller\Adminhtml\Category;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use RuntimeException;
use SmartOSC\Blog\Model\CategoryFactory;

/**
 * Class Save
 * @package SmartOSC\Blog\Controller\Adminhtml\Category
 */
class Save extends Action
{

    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(
        Action\Context $context,
        CategoryFactory $categoryFactory)
    {
        parent::__construct($context);
        $this->_categoryFactory = $categoryFactory;

    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->_categoryFactory->create();
            $id = $this->getRequest()->getParam('category_id');
            if ($id) {
                $model->load($id);
                if ($id != $model->getId()) {
                    throw new LocalizedException(__('The wrong category is specified.'));
                }
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'blogs_category_prepare_save', ['category' => $model, 'request' => $this->getRequest()]
            );

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this Category.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['category_id' => $id, '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the category.'));
            }

            $this->_getSession()->setFormData($data);
            if ($id) {
                return $resultRedirect->setPath('*/*/edit',
                    ['category_id' => $this->getRequest()->getParam('category_id')]);
            } else {
                return $resultRedirect->setPath('*/*/new');
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Tigren_Events::save');
    }
}