<?php


namespace Smart\Bloger\Controller\Adminhtml\Category;

use Magento\Framework\Exception\LocalizedException;
use Smart\Bloger\Controller\Adminhtml\Category;
use Magento\Backend\App\Action;
use Zend_Debug;


class Save extends Action
{
    protected $dataPersistor;
    private $categoryFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Smart\Bloger\Model\CategoryFactory $categoryFactory

    )
    {
        $this->dataPersistor = $dataPersistor;
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('category_id');
            //$prid = $this->getRequest()->getParam('parent_id');
            $model = $this->categoryFactory->create()->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Category no longer exists.'));
                return $resultRedirect->setPath('*/*/');

            }
            //var_dump($model->getId().'asdasd'.$id) ; die;
            $model->setData($data);
            if (!empty($data['parent_id']))
            {
                $model->setData('parent_id',$data['parent_id'][0]);
            }else{
                $model->setData('parent_id',0);
            }

           // \Zend_Debug::dump($model->setData($data));
           // \Zend_Debug($data);;
           // echo '<pre/>';
           // var_dump($model->getData());die;
           // var_dump($model->setData($data));die;
             //\Zend_Debug::dump($model->getData());die('asd');

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Category.'));
                $this->dataPersistor->clear('smart_bloger_category');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['category_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }

            $this->dataPersistor->set('smart_bloger_category', $data);

            return $resultRedirect->setPath('*/*/edit', ['category_id' => $this->getRequest()->getParam('category_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function redirectPath($resultRedirect, $id)
    {
        return $id ? $resultRedirect->setPath('*/*/edit', ['category_id' => $id]) : $resultRedirect->setPath('*/*/new');
    }
}
