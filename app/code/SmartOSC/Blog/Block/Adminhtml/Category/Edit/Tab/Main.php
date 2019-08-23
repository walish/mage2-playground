<?php


namespace SmartOSC\Blog\Block\Adminhtml\Category\Edit\Tab;


use IntlDateFormatter;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
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
 * @package SmartOSC\Blog\Block\Adminhtml\Category\Edit\Tab
 */
class Main extends Generic
{

    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var Category
     */
    protected $_categoryOption;

    /**
     * @var CategoryCollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * Main constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param Data $blogsHelper
     * @param Category $categoryOption
     * @param CategoryFactory $categoryFactory
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param Config $wysiwygConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        Data $blogsHelper,
        Category $categoryOption,
        CategoryFactory $categoryFactory,
        CategoryCollectionFactory $categoryCollectionFactory,
        Config $wysiwygConfig,
        array $data = [])
    {
        $this->_categoryOption = $categoryOption;
        $this->_categoryFactory = $categoryFactory;
        $this->wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_blogsHelper = $blogsHelper;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        $category = $this->_coreRegistry->registry('blogs_category');
        $categoryId = $category->getId();

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('category_');

        $fieldset = $form->addFieldset(
            'base_fieldset', ['legend' => __('General Information'), 'class' => 'fieldset-wide']
        );

        if ($categoryId) {
            $fieldset->addField('category_id', 'hidden', ['name' => 'category_id']);
        }

        $fieldset->addField(
            'category_name', 'text', [
                'name' => 'category_name',
                'label' => __('Category Name'),
                'title' => __('Category Name'),
                'required' => true
            ]
        );
        $listcategory = $this->_categoryOption->toOptionArray();
        $fieldset->addField(
            'parent_id', 'select', [
                'name' => 'parent_id',
                'label' => __('Parent Category'),
                'title' => __('Parent Category'),
                'required' => false,
                'values' => $listcategory
            ]
        );
//        $fieldset->addField(
//            'parent_id',
//            'checkboxes',[
//                'label' => __('Parent Category'),
//                'title' => __('Parent Category'),
//                'required' => true,
////                'options' => $this->_getParentCategoryOptions(),
//                'class' => 'validate-parent-category',
//                'name' => 'parent_id',
//            ]
//        );
//        $fieldset->addField(
//            'parent_id',
//            'select',[
//                'label' => __('Parent Category'),
//                'title' => __('Parent Category'),
//                'required' => true,
//                'options' => $this->_getParentCategoryOptions(),
//                'class' => 'validate-parent-category',
//                'name' => 'new_category_parent',
//            ]
////        );
//        if (!$this->isHideParent($category)) {
//            $categories = $this->_categoryCollectionFactory->create()
//                ->addAttributeToSelect('name')
//                ->toOptionArray();

//            $fieldset->addField('parent_id', 'radios', [
//                'label'    => __('Parent Category'),
//                'title' => __('Category Name'),
//                'name'     => 'parent_id',
////                'value'    => $category->getParentId() ? $category->getParentId() : 1,
////                'values'   => $categories,
//                'required' => true,
//            ]);
//        }

        $fieldset->addField('status', 'select', [
            'label' => __('Status'),
            'name' => 'status',
            'value' => $category->getStatus(),
            'values' => ['1' => __('Enabled'), '0' => __('Disabled')]
        ]);

        $form->setValues($category->getData());
        $this->setForm($form);
        return parent::_prepareForm(); // TODO: Change the autogenerated stub
    }

    /**
     * @return array
     */
    protected function _getParentCategoryOptions()
    {
        $result[] = ['label' => '', 'value' => ''];
        $items = $this->_categoryFactory->create()->getCollection()->addAttributeToSelect(
            'name'
        )->setPageSize(
            3
        )->load()->getItems();

        $result = [];

        if (count($items) === 2) {
            $item = array_pop($items);
            $result = [$item->getEntityId() => $item->getName()];
        }

        return $result;
    }

    /**
     * @param $category
     * @return bool
     */
    protected function isHideParent($category)
    {
        $categories = $this->_categoryCollectionFactory->create()
            ->addAttributeToSelect('name')
            ->toOptionArray();

        return $category->getParentId() === "0" || ($category->getParentId() === null && !count($categories));
    }
}