<?php


namespace Train\Blog\Controller\Adminhtml\Post;

use Train\Blog\Controller\Adminhtml\Post;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Save extends Action
{
    protected $dataPersistor;
    private $postFactory;
    protected $date;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Train\Blog\Model\PostFactory $postFactory,
        \Magento\Framework\Stdlib\DateTime\DateTime $date


    )
    {
        $this->date = $date;
        $this->dataPersistor = $dataPersistor;
        $this->postFactory = $postFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('post_id');
            $model = $this->postFactory->create()->load($id);

            if (isset($data['post_image'][0]['file'])) {
                $data['post_image'] = $data['post_image'][0]['file'];
            };
            if (isset($data['category_id']) && !empty($data['category_id'])) {
                $data['category_id'] = implode(',', $data['category_id']);
            }
            if (isset($data['product_id']) && !empty($data['product_id'])) {
                $data['product_id'] = implode(',', $data['product_id']);
            }
            $model->setData($data);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Post no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Post.'));
                $this->dataPersistor->clear('train_blog_post');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['post_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
            $this->dataPersistor->set('train_blog_post', $data);
            return $resultRedirect->setPath('*/*/edit', ['post_id' => $this->getRequest()->getParam('post_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function redirectPath($resultRedirect, $id)
    {
        return $id ? $resultRedirect->setPath('*/*/edit', ['post_id' => $id]) : $resultRedirect->setPath('*/*/new');
    }
}