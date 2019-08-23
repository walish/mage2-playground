<?php


namespace SmartOSC\Blog\Block\Adminhtml\Blog\Edit\Sidebar;

use Magento\Backend\Block\Widget\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use SmartOSC\Blog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Backend\Block\Widget\Context;

/**
 * Class Categories
 * @package SmartOSC\Blog\Block\Adminhtml\Blog\Edit\Sidebar
 */
class Categories extends Form
{

    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param CategoryCollectionFactory $postCollectionFactory
     * @param FormFactory $formFactory
     * @param Registry $registry
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        CategoryCollectionFactory $postCollectionFactory,
        FormFactory $formFactory,
        Registry $registry,
        Context $context,
        array $data = []
    )
    {
        $this->categoryCollectionFactory = $postCollectionFactory;
        $this->formFactory = $formFactory;
        $this->registry = $registry;

        parent::__construct($context, $data);
    }

    /**
     * @return \SmartOSC\Blog\Block\Adminhtml\Post\Edit\Sidebar\Categories
     *
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        $form = $this->formFactory->create();
        $this->setForm($form);

        $post = $this->registry->registry('blog_blog');

        $fieldset = $form->addFieldset('categories_fieldset', [
            'class' => 'blog__post-fieldset',
            'legend' => __('Categories'),
        ]);

        $collection = $this->categoryCollectionFactory->create()
            ->addAttributeToSelect(['name']);

        $fieldset->addField('category_ids', 'checkboxes', [
            'name' => 'post[category_ids][]',
            'value' => $post->getCategoryIds(),
            'values' => $collection->toOptionArray()
        ]);

        return parent::_prepareForm();
    }
}