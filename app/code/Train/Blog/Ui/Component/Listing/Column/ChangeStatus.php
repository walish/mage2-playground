<?php


namespace Train\Blog\Ui\Component\Listing\Column;


class ChangeStatus implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [['value' => 1, 'label' => __('Enable')], ['value' => 0, 'label' => __('Disable')]];
    }
}