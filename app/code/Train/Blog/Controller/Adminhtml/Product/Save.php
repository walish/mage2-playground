<?php


namespace Train\Blog\Controller\Adminhtml\Product;

use Train\Blog\Controller\Adminhtml\Product;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Save extends Action
{
    protected $dataPersistor;
    private $productFactory;
    protected $date;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Train\Blog\Model\ProductFactory $productFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date


    )
    {
        $this->date = $date;
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
            if (isset($data['product_image'])) {
                $data['product_image'] = $data['product_image'][0]['file'];
            }

            if (isset($data['category_id']) && !empty($data['category_id']) ) {
                $data['category_id'] = implode(',', $data['category_id']);
            }

           // if(isset($data['product_created'])){
            //    $data['product_created']=$this->date->gmtDate('H:i:s');
           // }

            $model->setData($data);

            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This product no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the product.'));
                $this->dataPersistor->clear('train_blog_product');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['product_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
             //   $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the product'));
            }
            $this->dataPersistor->set('train_blog_product', $data);
            return $resultRedirect->setPath('*/*/edit', ['product_id' => $this->getRequest()->getParam('product_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function redirectPath($resultRedirect, $id)
    {
        return $id ? $resultRedirect->setPath('*/*/edit', ['product_id' => $id]) : $resultRedirect->setPath('*/*/new');
    }
}