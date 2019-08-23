<?php

namespace SmartOSC\Blog\Controller\Adminhtml\Blog;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Helper\Js;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Psr\Log\LoggerInterface;
use SmartOSC\Blog\Helper\Data;
use SmartOSC\Blog\Model\BlogFactory;
use Tigren\Events\Model\EventFactory;

/**
 * Class Save
 * @package SmartOSC\Blog\Controller\Adminhtml\Blog
 */
class Save extends Action
{
    /**
     * @var Filesystem
     */
    protected $_fileSystem;

    /**
     * @var UploaderFactory
     */
    protected $_fileUploaderFactory;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var Js
     */
    protected $jsHelper;

    /**
     * @var DateTime
     */
    protected $_date;

    /**
     * @var EventFactory
     */
    protected $_blogFactory;

    /**
     * @var ProductFactory
     */
    protected $_productFactory;

    /**
     * @var TimezoneInterface
     */
    protected $_localeDate;

    /**
     * @var Data
     */
    protected $blogHelper;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param Filesystem $fileSystem
     * @param UploaderFactory $fileUploaderFactory
     * @param LoggerInterface $logger
     * @param Js $jsHelper
     * @param DateTime $date
     * @param BlogFactory $blogFactory
     * @param ProductFactory $productFactory
     * @param Data $helper
     * @param TimezoneInterface $localeDate
     */
    public function __construct(
        Action\Context $context,
        Filesystem $fileSystem,
        UploaderFactory $fileUploaderFactory,
        LoggerInterface $logger,
        Js $jsHelper,
        DateTime $date,
        BlogFactory $blogFactory,
        ProductFactory $productFactory,
        Data $helper,
        TimezoneInterface $localeDate
    ) {
        parent::__construct($context);
        $this->_fileSystem = $fileSystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_logger = $logger;
        $this->jsHelper = $jsHelper;
        $this->_date = $date;
        $this->_blogFactory = $blogFactory;
        $this->_productFactory = $productFactory;
        $this->_localeDate = $localeDate;
        $this->blogHelper = $helper;
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
            $blogModel = $this->_blogFactory->create();
            $id = $this->getRequest()->getParam('blog_id');
            if ($id) {
                $blogModel->load($id);
                if ($id != $blogModel->getId()) {
                    throw new LocalizedException(__('The wrong blog is specified.'));
                }
            }

            $localeDate = $this->_localeDate;

            if ($data['start_time']) {
                $data['start_time'] = $this->blogHelper->convertTime($data['start_time']);
            }
            if ($data['end_time']) {
                $data['end_time'] = $this->blogHelper->convertTime($data['end_time']);
            }

            if ($data['end_time'] && $data['start_time'] >= $data['end_time']) {
                $this->messageManager->addError(__('Public Time must be earlier than End Time.'));

                $this->_getSession()->setFormData($data);
                if ($id) {
                    return $resultRedirect->setPath('*/*/edit', ['blog_id' => $id]);
                } else {
                    return $resultRedirect->setPath('*/*/new');
                }
                return $resultRedirect->setPath('*/*/', ['_current' => true]);
            }

            /**
             * Categories
             */
            if (isset($data['categories'])) {
                $data['categories'] = $this->jsHelper->decodeGridSerializedInput($data['categories']);
            }

            if (isset($data['selected_products'])) {
                $data['selected_products'] = $this->jsHelper->decodeGridSerializedInput($data['selected_products']);
            }

            $imageRequest = $this->getRequest()->getFiles('image');
            /**
             * Process upload images
             */
            try {
                if (!empty($imageRequest['name'])) {
                    $path = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA)
                        ->getAbsolutePath('smartosc/blogs/blog/image/');

                    /**
                     * Remove old image
                     */
                    $oldName = !empty($data['old_image']) ? $data['old_image'] : '';
                    if ($oldName) {
                        @unlink($path . $oldName);
                    }
                    //find the first available name
                    $newName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $imageRequest['name']);
                    if (substr($newName, 0, 1) == '.') { // all non-english symbols
                        $newName = 'blog_' . $newName;
                    }
                    $i = 0;
                    while (file_exists($path . $newName)) {
                        $newName = ++$i . '_' . $newName;
                    }

                    /**
                     *
                     *
                     * @var $uploader Uploader
                     */
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'image']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->save($path, $newName);

                    $data['image'] = $newName;
                } else {
                    $oldName = !empty($data['old_image']) ? $data['old_image'] : '';
                    $data['image'] = $oldName;
                }
            } catch (Exception $e) {
                if ($e->getCode() != Uploader::TMP_NAME_EMPTY) {
                    $this->_logger->critical($e);
                }
            }

            //Banner
            $bannerRequest = $this->getRequest()->getFiles('banner');

            try {
                if (!empty($bannerRequest['name'])) {
                    $path = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA)
                        ->getAbsolutePath('smartosc/blogs/blog/image/');
                    // remove the old file
                    $oldName = !empty($data['old_banner']) ? $data['old_banner'] : '';
                    if ($oldName) {
                        @unlink($path . $oldName);
                    }
                    //find the first available name
                    $newName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $bannerRequest['name']);
                    if (substr($newName, 0, 1) == '.') { // all non-english symbols
                        $newName = 'blog_' . $newName;
                    }
                    $i = 0;
                    while (file_exists($path . $newName)) {
                        $newName = ++$i . '_' . $newName;
                    }

                    /** @var $uploader Uploader */
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'banner']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->save($path, $newName);

                    $data['banner'] = $newName;
                } else {
                    $oldName = !empty($data['old_banner']) ? $data['old_banner'] : '';
                    $data['banner'] = $oldName;
                }
            } catch (Exception $e) {
                if ($e->getCode() != Uploader::TMP_NAME_EMPTY) {
                    $this->_logger->critical($e);
                }
            }

            //Process delete images
            if (!empty($data['is_delete_image'])) {
                $path = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA)
                    ->getAbsolutePath('smartosc/blogs/blog/image/');
                // remove the old file
                $oldName = !empty($data['old_image']) ? $data['old_image'] : '';
                if ($oldName) {
                    @unlink($path . $oldName);
                }
                $data['image'] = '';
            }
            //delete banner
            if (!empty($data['is_delete_banner'])) {
                $path = $this->_fileSystem->getDirectoryRead(DirectoryList::MEDIA)
                    ->getAbsolutePath('smartosc/blogs/blog/image/');
                // remove the old file
                $oldName = !empty($data['old_banner']) ? $data['old_banner'] : '';
                if ($oldName) {
                    @unlink($path . $oldName);
                }
                $data['banner'] = '';
            }

            // Convert url youtube
            if (isset($data['url_youtube']) && $data['url_youtube']) {
                $data['url_youtube'] = str_replace('watch?v=', 'embed/', $data['url_youtube']);
            }

            $blogModel->setData($data);

            $this->_eventManager->dispatch(
                'blogs_blog_prepare_save',
                ['blog' => $blogModel, 'request' => $this->getRequest()]
            );

            try {
                $blogModel->save();
                $this->messageManager->addSuccess(__('You saved this Blog.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        ['blog_id' => $blogModel->getId(), '_current' => true]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the blog.'));
            }

            $this->_getSession()->setFormData($data);
            if ($id) {
                return $resultRedirect->setPath('*/*/edit', ['blog_id' => $id]);
            } else {
                return $resultRedirect->setPath('*/*/new');
            }
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('SmartOSC_Blog::save');
    }
}
