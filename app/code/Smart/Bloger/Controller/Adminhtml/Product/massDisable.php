<?php


namespace Smart\Bloger\Controller\Adminhtml\Product;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

class massDisable extends \Magento\Backend\App\Action
{

    protected $filter;

    protected $collectionFactory;

    public function __construct(
        Context $context,
        Filter $filter,
        \Smart\Bloger\Model\ResourceModel\Product\CollectionFactory $collectionFactory)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        foreach ($collection->getItems() as $item) {
            $item->setProductStatus(false);
            $item->save();
        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been disabled.', $collection->getSize()));


        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}