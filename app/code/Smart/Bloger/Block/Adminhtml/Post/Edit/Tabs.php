<?php


namespace Smart\Bloger\Block\Adminhtml\Post\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Magento\Framework\Translate\InlineInterface;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
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

    public function getBlog()
    {
        if (!$this->getData('bloger_post') instanceof \Smart\Bloger\Model\Post) {
            $this->setData('bloger_post', $this->_coreRegistry->registry('bloger_post'));
        }
        return $this->getData('bloger_post');
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('blogs_blog_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Post'));
    }

    protected function _beforeToHtml()
    {
//        $this->addTab(
//            'post',
//            [
//                'label' => __('Post info'),
//                'url' => $this->getUrl('/*/save', ['_current' => true]),
//                'class' => 'ajax'
//            ]
//        );

//        $this->addTab(
//            'category',
//            [
//                'label' => __('Category'),
//                'url' => $this->getUrl('smart_bloger/*/index', ['_current' => true]),
//                'class' => 'ajax'
//            ]
//        );
//        $this->addTab(
//            'productgrid',
//            [
//                'label' => __('Select Product'),
//                'url' => $this->getUrl('smart_bloger/*/productgrid', ['_current' => true]),
//                'class' => 'ajax',
//
//            ]
//        );
        $this->addTab('products_section', [
            'label'   => __('Related Products'),
            'content' => $this->getLayout()
                ->createBlock('Smart\Bloger\Block\Adminhtml\Post\Edit\Tab\Productgrid')->toHtml(),
        ]);


        return parent::_beforeToHtml();

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