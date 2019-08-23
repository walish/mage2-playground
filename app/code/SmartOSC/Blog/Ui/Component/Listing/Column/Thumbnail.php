<?php

namespace SmartOSC\Blog\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Thumbnail
 * @package SmartOSC\Blog\Ui\Component\Listing\Column
 */
class Thumbnail extends Column
{
    /**
     *
     */
    const NAME = 'image';
    /**
     * @var
     */
    protected $imageblogHelper;

    /**
     * Thumbnail constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        \SmartOSC\Blog\Helper\Data $imageblogHelper,
        array $components = [],
        array $data = []
        )
    {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data);
        $this->imageblogHelper = $imageblogHelper;
        $this->urlBuilder = $urlBuilder;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $blog = new \Magento\Framework\DataObject($item);
                $item[$fieldName . '_src'] = $this->imageblogHelper->getImageUrl($blog->getImage());
                $item[$fieldName . '_alt'] = $blog->getImage();
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    'blogs/blog/edit',
                    ['id' => $blog->getId()]
                );
                $item[$fieldName . '_orig_src'] = $this->imageblogHelper->getImageUrl($blog->getImage());
            }
        }

        return $dataSource;
    }
}
