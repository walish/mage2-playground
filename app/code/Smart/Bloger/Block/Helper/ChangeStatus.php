<?php


namespace Smart\Bloger\Block\Helper;
use Magento\Framework\Option\ArrayInterface;
class ChangeStatus implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => ('Enable')], ['value' => 0, 'label' => ('Disable')]
        ];
    }
}