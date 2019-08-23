<?php


namespace SmartOSC\Blog\Block\Adminhtml\Blog\Edit\Tab;


use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use IntlDateFormatter;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;
use SmartOSC\Blog\Helper\Data;
use SmartOSC\Blog\Model\CategoryFactory;
use SmartOSC\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use SmartOSC\Blog\Model\Source\Category;

/**
 * Class Main
 * @package SmartOSC\Blog\Block\Adminhtml\Blog\Edit\Tab
 */
class Main extends Generic
{

    /**
     * @var Config
     */
    protected $wysiwygConfig;
    /**
     * @var Category
     */
    protected $_categoryOption;

    /**
     * @var Store
     */
    protected $_systemStore;

    /**
     * @var Data
     */
    protected $_blogsHelper;

    /**
     * Main constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param Category $categoryOption
     * @param Data $blogsHelper
     * @param Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        Category $categoryOption,
        Data $blogsHelper,
        Config $wysiwygConfig,
        array $data = [])
    {
        $this->_categoryOption = $categoryOption;
        $this->wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        $this->_blogsHelper = $blogsHelper;
        parent::__construct($context, $registry, $formFactory, $data);
    }


    /**
     * @return Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _prepareForm()
    {
        $blog = $this->_coreRegistry->registry('blogs_blog');
        $blogId = $blog->getId();

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('blog_');

        $fieldset = $form->addFieldset(
            'base_fieldset', ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($blogId) {
            $fieldset->addField('blog_id', 'hidden', ['name' => 'blog_id']);
        }

        $fieldset->addField(
            'status', 'select', [
                'name' => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );
        $asdasdas = $this->_categoryOption->toOptionArray();
        $fieldset->addField(
            'category_id', 'select', [
                'name' => 'category_id',
                'label' => __('Parent Category'),
                'title' => __('Parent Category'),
                'required' => false,
                'values' => $asdasdas,
            ]
        );
        $fieldset->addField(
            'blog_name', 'text', [
                'name' => 'blog_name',
                'label' => __('Blog Name'),
                'title' => __('Blog Name'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'short_content', 'editor', [
                'rows' => '5',
                'cols' => '10',
                'wysiwyg' => true,
                'config' => $this->wysiwygConfig->getConfig(),
                'name' => 'short_content',
                'label' => __('Short Content'),
                'title' => __('Short Content')
            ]
        );


        $fieldset->addField(
            'value', 'editor', [
                'rows' => '5',
                'cols' => '30',
                'wysiwyg' => true,
                'config' => $this->wysiwygConfig->getConfig(),
                'name' => 'value',
                'label' => __('Content'),
                'title' => __('Content')
            ]
        );

        $imageDisplay = '';
        if ($blog->getImage()) {
            $imageDisplay .= $this->getImageHtml('image', $blog->getImage(), 'smartosc/blogs/blog/image/');
            $imageDisplay .= $this->getDeleteCheckboxHtml();
        }
        $fieldset->addField(
            'image', 'file', [
                'name' => 'image',
                'label' => __('Blog Image'),
                'title' => __('Blog Image'),
                'after_element_html' => $imageDisplay
            ]
        );

        $style = 'color: #000;background-color: #fff; font-weight: bold; font-size: 13px;';
        $dateFormat = $this->_localeDate->getDateFormat(IntlDateFormatter::SHORT);
        $timeFormat = $this->_localeDate->getTimeFormat(IntlDateFormatter::SHORT);

        $fieldset->addField(
            'start_time', 'date', [
                'name' => 'start_time',
                'label' => __('Public Date'),
                'title' => __('Public Date'),
                'style' => $style,
                'required' => false,
                'class' => __('validate-date'),
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'note' => $this->_localeDate->getDateTimeFormat(IntlDateFormatter::SHORT)
            ]
        );

        $fieldset->addField(
            'end_time', 'date', [
                'name' => 'end_time',
                'label' => __('End Date'),
                'title' => __('End Date'),
                'style' => $style,
                'required' => false,
                'class' => __('validate-date'),
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'note' => $this->_localeDate->getDateTimeFormat(IntlDateFormatter::SHORT)
            ]
        );

        $fieldset->addField(
            'quote_banner', 'text', [
                'name' => 'quote_banner',
                'label' => __('Quote for Banner'),
                'title' => __('Quote for Banner'),
                'required' => true
            ]
        );

        $bannerDisplay = '';
        if ($blog->getBanner()) {
            $bannerDisplay .= $this->getBannerHtml('banner', $blog->getBanner(), 'smartosc/blogs/blog/image/');
            $bannerDisplay .= $this->getDeleteCheckboxBanner();
        }
        $fieldset->addField(
            'banner', 'file', [
                'name' => 'banner',
                'label' => __('Banner'),
                'title' => __('Banner'),
                'after_element_html' => $bannerDisplay
            ]
        );

        $fieldset->addField(
            'url_youtube', 'text', [
                'name' => 'url_youtube',
                'label' => __('URL Youtube'),
                'title' => __('URL Youtube')
            ]
        );

        /**
         * Check if store has only one store view
         */
        if (!$this->_storeManager->hasSingleStore()) {
            $field = $fieldset->addField(
                'select_stores', 'multiselect', [
                    'label' => __('Store View'),
                    'required' => true,
                    'name' => 'stores[]',
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
            $blog->setSelectStores($blog->getStores());
        } else {
            $fieldset->addField(
                'select_stores', 'hidden', [
                    'name' => 'stores[]',
                    'value' => $this->_storeManager->getStore(true)->getId()
                ]
            );
            $blog->setSelectStores($this->_storeManager->getStore(true)->getId());
        }


        $form->setValues($blog->getData());
        $this->setForm($form);
        return parent::_prepareForm(); // TODO: Change the autogenerated stub
    }

    /**
     * @param $field
     * @param $imageName
     * @param $dir
     * @return string
     */
    protected function getImageHtml($field, $imageName, $dir)
    {
        $html = '';
        if ($imageName) {
            $html .= '<p style="margin-top: 5px">';
            $html .= '<image style="min-width:100px;max-width:50%;" src="' . $this->_blogsHelper->getImageUrl($imageName,
                    $dir) . '" />';
            $html .= '<input type="hidden" value="' . $imageName . '" name="old_' . $field . '"/>';
            $html .= '</p>';
        }
        return $html;
    }

    /**
     * @return string
     */
    protected function getDeleteCheckboxHtml()
    {
        $html = '';
        $html .= '<span class="delete-image">'
            . '<input type="checkbox" name="is_delete_image" class="checkbox" id="is_delete_image">'
            . '<label for="is_delete_image"> Delete Image</label>'
            . '</span>';
        return $html;
    }

    /**
     * @param $field
     * @param $imageName
     * @param $dir
     * @return string
     */
    protected function getBannerHtml($field, $imageName, $dir)
    {
        $html = '';
        if ($imageName) {
            $html .= '<p style="margin-top: 5px">';
            $html .= '<image style="min-width:100px;max-width:50%;" src="' . $this->_blogsHelper->getImageUrl($imageName,
                    $dir) . '" />';
            $html .= '<input type="hidden" value="' . $imageName . '" name="old_' . $field . '"/>';
            $html .= '</p>';
        }
        return $html;
    }

    /**
     * @return string
     */
    protected function getDeleteCheckboxBanner()
    {
        $html = '';
        $html .= '<span class="delete-banner">'
            . '<input type="checkbox" name="is_delete_banner" class="checkbox" id="is_delete_banner">'
            . '<label for="is_delete_banner"> Delete Banner</label>'
            . '</span>';
        return $html;
    }
}