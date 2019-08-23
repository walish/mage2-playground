<?php


namespace SmartOSC\Blog\Controller\Adminhtml;


use \Magento\Backend\App\Action;

/**
 * Class Category
 * @package SmartOSC\Blog\Controller\Adminhtml
 */
class Category extends Action
{

    /**
     * @var string
     */
    protected $_formSessionKey = 'blog_category_edit_data';

    /**
     * Allowed Key
     * @var string
     */
    protected $_allowedKey = 'SmartOSC_Blog::category';

    /**
     * Model class name
     * @var string
     */
    protected $_modelClass = 'SmartOSC\Blog\Model\Category';

    /**
     * Active menu key
     * @var string
     */
    protected $_activeMenu = 'SmartOSC_Blog::category';

    /**
     * Status field name
     * @var string
     */
    protected $_statusField = 'is_active';


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        // TODO: Implement execute() method.
    }
}