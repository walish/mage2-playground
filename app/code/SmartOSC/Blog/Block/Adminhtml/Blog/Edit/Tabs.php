<?php


namespace SmartOSC\Blog\Block\Adminhtml\Blog\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Framework\Translate\InlineInterface;
use SmartOSC\Blog\Model\Blog;

/**
 * Class Tabs
 * @package SmartOSC\Blog\Block\Adminhtml\Blog\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var InlineInterface
     */
    protected $_translateInline;

    /**
     * Core registry
     *
     * @var Registry
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
        if (!$this->getData('blogs_blog') instanceof Blog) {
            $this->setData('blogs_blog', $this->_coreRegistry->registry('blogs_blog'));
        }
        return $this->getData('blogs_blog');
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blogs_blog_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Blog'));
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
                'label' => __('Blog Information'),
                'content' => $this->getLayout()->createBlock(
                    'SmartOSC\Blog\Block\Adminhtml\Blog\Edit\Tab\Main'
                )->toHtml()
            ]
        );

//        $this->addTab(
//            'category',
//            [
//                'label' => __('Category'),
//                'url' => $this->getUrl('blogs/*/category', ['_current' => true]),
//                'class' => 'ajax'
//            ]
//        );
        $this->addTab(
            'product',
            [
                'label' => __('Related Product'),
                'url' => $this->getUrl('blogs/*/productgrid', ['_current' => true]),
                'class' => 'ajax'
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