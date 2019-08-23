<?php


namespace SmartOSC\Blog\Block;


use Magento\Framework\Module\Manager;
use Magento\Framework\View\Element\Html\Link;
use Magento\Framework\View\Element\Template\Context;
use SmartOSC\Blog\Helper\Data;

/**
 * Class HeaderLink
 * @package SmartOSC\Blog\Block
 */
class HeaderLink extends Link
{

    /**
     * @var Manager
     */
    protected $_moduleManager;

    /**
     * @var Data
     */
    protected $_blogsHelper;

    /**
     * HeaderLink constructor.
     * @param Context $context
     * @param Manager $moduleManager
     * @param Data $blogsHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Manager $moduleManager,
        Data $blogsHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_moduleManager = $moduleManager;
        $this->_blogsHelper = $blogsHelper;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return $this->getUrl('blogs', ['_secure' => true]);
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    public function getLabel()
    {
        return __('SmartOSC Blog');
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        return parent::_toHtml();
    }
}