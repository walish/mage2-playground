<?php


namespace SmartOSC\Blog\Ui\Post\Source;


use Magento\Framework\Option\ArrayInterface;
use SmartOSC\Blog\Api\Data\PostInterface;
class Status implements ArrayInterface
{

    public function toOptionArray()
    {
        return [
            [
                'label' => __('Enable'),
                'value' => PostInterface::STATUS_ENABLE,
            ],
            [
                'label' => __('DISABLE'),
                'value' => PostInterface::STATUS_DISABLE,
            ],
        ];
    }
}