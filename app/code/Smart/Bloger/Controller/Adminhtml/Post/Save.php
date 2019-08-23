<?php


namespace Smart\Bloger\Controller\Adminhtml\Post;


use Magento\Framework\Exception\LocalizedException;
use Smart\Bloger\Controller\Adminhtml\Post;
use Magento\Backend\App\Action;


class Save extends Action
{
    protected $dataPersistor;
    private $postFactory;
    private $catpostFactory;
    private $postproductFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Smart\Bloger\Model\PostFactory $postFactory,
        \Smart\Bloger\Model\CatPostFactory $catpostFactory,
        \Smart\Bloger\Model\PostProductFactory $postproductFactory

    )
    {
        $this->dataPersistor = $dataPersistor;
        $this->postFactory = $postFactory;
        $this->catpostFactory=$catpostFactory;
        $this->postproductFactory=$postproductFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('post_id');
            $catid = $this->getRequest()->getParam('category_id');
            $productid = $this->getRequest()->getParam('product_id');
            $modelpost = $this->postFactory->create()->load($id);
            $modelcatpost = $this->catpostFactory->create();
            $modelpostproduct = $this->postproductFactory->create();




            if (!$modelpost->getPostId() && $id) {
                $this->messageManager->addErrorMessage(__('This Post no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $image=$modelpost->getData('post_image');
            $modelpost->setData($data);
            //\Zend_Debug::dump($modelpost->getData());die;


            /** delete post_id in post_product table*/
            $this->deletepostidinpostproduct($id);
            /** delete post_id in cat_post table*/
            $this->deletepostidincatpost($id);

            /** insert image in post table*/
            if (isset($data['post_image'][0]['url']))
            {
                $modelpost->setData('post_image',$data['post_image'][0]['url']);
            }else {
                $modelpost->setData('post_image',$image);
            }

            try {
                $modelpost->save();
                $idpostnew=$modelpost->getId();
                /** get cat_id and save*/
                if (!empty($catid))
                {
                    $newdata=[];
                    foreach ($catid as $k=>$v)
                    {
                        array_push($newdata,['post_id'=>$idpostnew,'category_id'=>$v]);
                    }
                    foreach ($newdata as $k=>$v)
                    {
                        $modelcatpost->setData($v);
                        $modelcatpost->save();
                       // \Zend_Debug::dump($modelcatpost->getData());
                    }
                }else{
                    $this->messageManager->addErrorMessage(__('Please choose item category id'));
                    return $resultRedirect->setPath('*/*/', ['post_id' => $modelpost->getId()]);
                }

                /** get pro_id and save*/
                if (!empty($productid))
                {
                    $newdatapro=[];
                    foreach ($productid as $k=>$v)
                    {
                        array_push($newdatapro,['post_id'=>$idpostnew,'product_id'=>$v]);
                    }
                    foreach ($newdatapro as $k=>$v)
                    {
                        $modelpostproduct->setData($v);
                        $modelpostproduct->save();
                        //\Zend_Debug::dump($modelpostproduct->getData());
                    }
                }else{
                    $this->messageManager->addErrorMessage(__('Please choose item product id'));
                    return $resultRedirect->setPath('*/*/', ['post_id' => $modelpost->getId()]);
                }

                $this->messageManager->addSuccessMessage(__('You saved the Post.'));
                $this->dataPersistor->clear('smart_bloger_post');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['post_id' => $modelpost->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }

            $this->dataPersistor->set('smart_bloger_post', $data);

            return $resultRedirect->setPath('*/*/edit', ['post_id' => $this->getRequest()->getParam('post_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function redirectPath($resultRedirect, $id)
    {
        return $id ? $resultRedirect->setPath('*/*/edit', ['post_id' => $id]) : $resultRedirect->setPath('*/*/new');
    }
    private function deletepostidincatpost($id)
    {
        $modelcatpostnew=$this->catpostFactory->create()->getCollection();
        $modelcatpostnew->addFieldToFilter('post_id',['eq'=>$id]);
        foreach ($modelcatpostnew as $key=>$value)
        {
            //$value->getData('post_id');die;
            $value->delete();
        }
    }
    private function deletepostidinpostproduct($id)
    {
        $modelpostproductnew = $this->postproductFactory->create()->getCollection();
        $modelpostproductnew->addFieldToFilter('post_id',['eq'=>$id]);
        foreach ($modelpostproductnew as $key=>$value)
        {
            $value->delete();
        }
    }
}
