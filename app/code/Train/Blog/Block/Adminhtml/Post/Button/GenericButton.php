<?php


namespace Train\Blog\Block\Adminhtml\Post\Button;

use Magento\Backend\Block\Widget\Context;
abstract class GenericButton
{
    protected $context;
    public function __construct(Context $context)
    {
        $this->context = $context;
    }
    /**
     * Return model ID
     *
     * @return int|null
     */
    public function getModelId()
    {
        return $this->context->getRequest()->getParam('post_id');
    }
    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}