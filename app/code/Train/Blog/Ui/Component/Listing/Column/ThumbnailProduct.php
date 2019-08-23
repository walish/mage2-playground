<?php


namespace Train\Blog\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Asset\Repository;
class ThumbnailProduct extends \Magento\Ui\Component\Listing\Columns\Column
{

    private $storeManager;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    private $assetRepo;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        Repository $assetRepo,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
        $this->assetRepo = $assetRepo;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $path = $this->storeManager->getStore()->getBaseUrl(
                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                ) . 'catalog/tmp/category/';

            $baseImage = $this->assetRepo->getUrl('Train_Blog::upload/product.png');
            foreach ($dataSource['data']['items'] as & $item) {
                if ($item['product_image']) {
                    $item['product_image' . '_src'] = $path . $item['product_image'];
                    $item['product_image' . '_alt'] = $item['product_image'];
                    $item['product_image' . '_orig_src'] = $path . $item['product_image'];
                }else{
                    $item['product_image' . '_src'] = $baseImage;
                    $item['product_image' . '_alt'] = 'Image false';
                    $item['product_image' . '_orig_src'] = $baseImage;
                }
            }
        }
        return $dataSource;
    }
}