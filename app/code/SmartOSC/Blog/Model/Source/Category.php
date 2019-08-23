<?php


namespace SmartOSC\Blog\Model\Source;


class Category implements \Magento\Framework\Data\OptionSourceInterface

{

    protected  $_category;


    protected $_collectionFactory;
    public function __construct(
        \SmartOSC\Blog\Model\Category $category)
    {
        $this->_category = $category;

    }
    /**
     * Get options
     *
     * @return array
     */
//
    public function toOptionArray()
    {
        $options[] = ['label' => '', 'value' => ''];
               $categoryCollection = $this->_category->getCollection()
            ->addFieldToSelect('category_id')
            ->addFieldToSelect('category_name');
        foreach ($categoryCollection as $category) {
            $options[] = [
                'label' => $category->getCategoryName(),
                'value' => $category->getCategoryId(),
            ];
        }
        return $options;
    }
//    public function toOptionArray()
//    {
//        if (!$this->options) {
//            $this->_collectionFactory->setOrder('category_id', 'ASC');
//            $this->options = $this->_collectionFactory->toOptionArray();
//        }
//        return $this->options;
//    }
}