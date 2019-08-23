<?php


namespace Train\Blog\Controller\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
class Category extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $request;
    protected $categoryFactory;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\App\RequestInterface $request,
        CategoryFactory $categoryFactory)
    {
        $this->_pageFactory = $pageFactory;
        $this->categoryFactory=$categoryFactory;
        $this->request = $request;
        return parent::__construct($context);
    }

    public function execute()
    {
        return $this->_pageFactory->create();
    }
}