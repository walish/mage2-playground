<?php

namespace Smart\Bloger\Model\Post;

use Magento\Framework\App\Request\DataPersistorInterface;
use Smart\Bloger\Model\ResourceModel\Post\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;

    protected $storeManager;

    protected $dataPersistor;

    protected $catPostFactory;

    protected $loadedData;

    protected $postproductFactory;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $pageCollectionFactory,
        \Smart\Bloger\Model\ResourceModel\CatPost\CollectionFactory $catPostFactory,
        \Smart\Bloger\Model\ResourceModel\PostProduct\CollectionFactory $postproductFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $pageCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager=$storeManager;
        $this->catPostFactory = $catPostFactory;
        $this->postproductFactory=$postproductFactory;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $page) {
            $collection = $this->catPostFactory->create();
            $collectionpro=$this->postproductFactory->create();
            $collection->addFieldToFilter('post_id', ['eq' => $page->getId()]);
            $collectionpro->addFieldToFilter('post_id', ['eq' => $page->getId()]);

            $category_id = [];
            foreach ($collection as $catPost) {
                $category_id[] = $catPost->getData('category_id');
            }
            $product_id = [];
            foreach ($collectionpro as $postPro) {
                $product_id[] = $postPro->getData('product_id');
            }
            $page->setData('category_id', implode(',', $category_id));
            $page->setData('product_id', implode(',', $product_id));

            if ($page->getPostImage()) {
                $this->loadedData[$page->getId()] = $page->getData();
                //$post_image['post_image'][0]['name'] = $page->getPostImage();
                $post_image['post_image'][0]['url'] = $page->getPostImage();
            }

            $this->loadedData[$page->getId()] = $page->getData();
            $fullData = $this->loadedData;
            $this->loadedData[$page->getId()] = array_merge($fullData[$page->getId()], $post_image);
            //\Zend_Debug::dump($post_image);
           //\Zend_Debug::dump($a);die;
        }

        $data = $this->dataPersistor->get('smart_bloger_post');
        if (!empty($data)) {
            $page = $this->collection->getNewEmptyItem();
            $page->setData($data);
            $this->loadedData[$page->getId()] = $page->getData();
            $this->dataPersistor->clear('smart_bloger_post');
        }
        return $this->loadedData;
    }
    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/tmp/category/';
        return $mediaUrl;
    }
}
