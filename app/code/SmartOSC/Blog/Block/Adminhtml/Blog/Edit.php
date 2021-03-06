<?php

namespace SmartOSC\Blog\Block\Adminhtml\Blog;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form\Container;
use Magento\Framework\Registry;

/**
 * Class Edit
 * @package SmartOSC\Blog\Block\Adminhtml\Blog
 */
class Edit extends Container
{
    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * Edit constructor.
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $registry;
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getHeaderText()
    {
        $model = $this->_coreRegistry->registry('blogs_blog');
        if ($model->getId()) {
            return __("Edit Blogs '%1'", $this->escapeHtml($model->getTitle()));
        } else {
            return __('New Blog');
        }
    }

    /**
     *
     */
    protected function _construct()
    {
        $this->_objectId = 'blog_id';
        $this->_blockGroup = 'SmartOSC_Blog';
        $this->_controller = 'adminhtml_blog';

        parent::_construct();

        if ($this->_isAllowedAction('SmartOSC_Blog::manage_blogs_save')) {
            $this->buttonList->update('save', 'label', __('Save Blog'));
            $this->buttonList->add(
                'saveandcontinue',
                [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
                -100
            );
        } else {
            $this->buttonList->remove('save');
        }

        if ($this->_isAllowedAction('SmartOSC_Blog::manage_blogs_delete')) {
            $this->buttonList->update('delete', 'label', __('Delete Blog'));
        } else {
            $this->buttonList->remove('delete');
        }
    }

    /**
     * @param $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('blogs/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}
