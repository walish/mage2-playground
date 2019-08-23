<?php


namespace SmartOSC\Blog\Block\Adminhtml\Category\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Framework\Translate\InlineInterface;
use SmartOSC\Blog\Model\Category;

/**
 * Class Tabs
 * @package SmartOSC\Blog\Block\Adminhtml\Category\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var InlineInterface
     */
    protected $_translateInline;

    /**
     * @var Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * Tabs constructor.
     * @param Context $context
     * @param EncoderInterface $jsonEncoder
     * @param Session $authSession
     * @param Registry $registry
     * @param InlineInterface $translateInline
     * @param array $data
     */
    public function __construct(
        Context $context,
        EncoderInterface $jsonEncoder,
        Session $authSession,
        Registry $registry,
        InlineInterface $translateInline,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->_translateInline = $translateInline;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    /**
     * @return mixed
     */
    public function getBlog()
    {
        if (!$this->getData('blogs_category') instanceof Category) {
            $this->setData('blogs_category', $this->_coreRegistry->registry('blogs_category'));
        }
        return $this->getData('blogs_category');
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blogs_category_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Category'));
    }

    /**
     * @return \Magento\Backend\Block\Widget\Tabs
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        $this->addTab(
            'main',
            [
                'label' => __('Category Information'),
                'content' => $this->getLayout()->createBlock(
                    'SmartOSC\Blog\Block\Adminhtml\Category\Edit\Tab\Main'
                )->toHtml()
            ]
        );

        return parent::_prepareLayout();
    }

    /**
     * Translate html content
     *
     * @param string $html
     * @return string
     */
    protected function _translateHtml($html)
    {
        $this->_translateInline->processResponseBody($html);
        return $html;
    }

}