<?php


namespace Smart\Bloger\Block\Adminhtml;
/**
 * Adminhtml cms pages content block
 */

class Category extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_category';
        $this->_blockGroup = 'Smart_Bloger';
        $this->_headerText = __('Manage Category');

        parent::_construct();

        if ($this->_isAllowedAction('Smart_Bloger::save')) {
            $this->buttonList->update('add', 'label', __('Add New Category'));
        } else {
            $this->buttonList->remove('add');
        }
    }

    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}