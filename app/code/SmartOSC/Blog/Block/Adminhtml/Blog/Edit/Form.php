<?php

namespace SmartOSC\Blog\Block\Adminhtml\Blog\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Form
 * @package SmartOSC\Blog\Block\Adminhtml\Blog\Edit
 */
class Form extends Generic
{
    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blog_form');
        $this->setTitle(__('Blog Information'));
    }

    /**
     * Prepare form
     *
     * @return Generic
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'class' => 'admin__scope-old',
                    'action' => $this->getUrl('blogs/blog/save'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ],
            ]
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
