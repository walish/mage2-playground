<?php


namespace SmartOSC\Blog\Controller\Adminhtml\Blog;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\LayoutFactory;

/**
 * Class Product
 * @package SmartOSC\Blog\Controller\Adminhtml\Blog
 */
class Product extends Action
{

    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * Product constructor.
     * @param Context $context
     * @param LayoutFactory $resultLayoutFactory
     */
    public function __construct(
        Context $context,
        LayoutFactory $resultLayoutFactory
    )
    {
        parent::__construct($context);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Layout
     */
    public function execute()
    {
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('blogs.edit.tab.productgrid')
            ->setSelectedProducts($this->getRequest()->getPost('selected_products', null));
        return $resultLayout;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SmartOSC_Blog::manage_blogs');
    }

}