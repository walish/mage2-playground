<?php


namespace SmartOSC\Blog\Controller\Adminhtml\Blog;


use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use SmartOSC\Blog\Model\BlogFactory;

/**
 * Class Delete
 * @package SmartOSC\Blog\Controller\Adminhtml\Blog
 */
class Delete extends Action
{
    /**
     * @var PageFactory
     */
    protected $_pageFactory;

    /**
     * @var
     */
    protected $_blogRepository;

    /**
     * Delete constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param BlogFactory $blogFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        BlogFactory $blogFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_blogFactory = $blogFactory;
        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('blog_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_blogFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The blog has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['blog_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a blog to delete.'));
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