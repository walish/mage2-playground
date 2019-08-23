<?php


namespace SmartOSC\Blog\Model\Blog\Source;


class Status implements \Magento\Framework\Data\OptionSourceInterface
{

    protected $_model;

    public function __construct(
        \SmartOSC\Blog\Model\Blog $model
    )
    {
        $this->_model = $model;
    }

    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
        $availableOptions = $this->_model->getAvailableStatuses();
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}