<?php


namespace Smart\Bloger\Controller\Adminhtml\Product;

use Magento\Framework\Exception\LocalizedException;
use Smart\Bloger\Controller\Adminhtml\Product;
use Magento\Backend\App\Action;

class Save extends Action
{
    protected $dataPersistor;
    private $productFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Smart\Bloger\Model\ProductFactory $productFactory

    )
    {
        $this->dataPersistor = $dataPersistor;
        $this->productFactory = $productFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('product_id');
            $model = $this->productFactory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Product no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $collection = $model->getCollection();
            $image=$model->getData('product_image');;

            $model->setData($data);

            if (isset($data['product_image'][0]['url']))
            {
                $model->setData('product_image',$data['product_image'][0]['url']);
            }else {
                $model->setData('product_image',$image);
            }
           // \Zend_Debug::dump($model->getData());die('xxx');
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Product.'));
                $this->dataPersistor->clear('smart_bloger_product');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['product_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }

            $this->dataPersistor->set('smart_bloger_product', $data);

            return $resultRedirect->setPath('*/*/edit', ['product_id' => $this->getRequest()->getParam('product_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function redirectPath($resultRedirect, $id)
    {
        return $id ? $resultRedirect->setPath('*/*/edit', ['product_id' => $id]) : $resultRedirect->setPath('*/*/new');
    }
}
