<?php


namespace SmartOSC\Blog\Block;


use Magento\Framework\Module\Manager;
use Magento\Framework\View\Element\Html\Link;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class TopLink
 * @package SmartOSC\Blog\Block
 */
class TopLink extends Link
{
    /**
     * @var Manager
     */
    protected $_moduleManager;

    /**
     * TopLink constructor.
     * @param Context $context
     * @param Manager $moduleManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        Manager $moduleManager,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_moduleManager = $moduleManager;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getHref()
    {
        return $this->getUrl('blogs/wishlist/index');
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getLabel()
    {
        return __('SmartOSC');
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->_moduleManager->isOutputEnabled(
            'SmartOSC_Blog'
        )
        ) {
            return '';
        }
        return parent::_toHtml();
    }

}