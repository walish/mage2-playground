<?php

namespace SmartOSC\Blog\Block\Adminhtml\Blog\Edit;

use Exception;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Locale\ResolverInterface;
use Zend_Locale_Data;
use Zend_Locale_Exception;

/**
 * Class Sidebar
 * @package SmartOSC\Blog\Block\Adminhtml\Blog\Edit
 */
class Sidebar extends Template
{

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * Sidebar constructor.
     * @param ResolverInterface $localeResolver
     * @param Registry $registry
     * @param Context $context
     */
    public function __construct(
        ResolverInterface $localeResolver,
        Registry $registry,
        Context $context
    )
    {
        $this->localeResolver = $localeResolver;
        $this->registry = $registry;

        parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->registry->registry('blogs_blog');
    }

    /**
     * @param string $param
     * @param string $default
     * @return string
     * @throws Zend_Locale_Exception
     */
    public function getLocaleData($param, $default = '')
    {
        try {
            $text = Zend_Locale_Data::getContent($this->localeResolver->getLocale(), $param);
        } catch (Exception $e) {
            $text = $default;
        }

        return $text;
    }
}
