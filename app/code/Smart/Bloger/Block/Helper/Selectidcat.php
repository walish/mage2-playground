<?php

namespace Smart\Bloger\Block\Helper;
class Selectidcat extends \Magento\Framework\View\Element\Template implements \Magento\Framework\Option\ArrayInterface
{
    protected $categoryFactory;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,\Smart\Bloger\Model\CategoryFactory $categoryFactory)
    {
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    public function toOptionArray()
    {
//        return [
//           ['value' => 1, 'label' => __('Test One')],
//           ['value' => 2, 'label' => __('Test Two')],
//           ['value' => 3, 'label' => __('Test Three')],
//        ];
        $category = $this->categoryFactory->create();
        $collection = $category->getCollection();
        $return=[];
//        foreach($keys as $key => $value){
//            $arr_test[] = array_push($arr,$key);
//            array_push($arr,$value);
//            if(!in_array($arr,$list_arr)){
//                array_push($list_arr,$arr);
//            }
//
//        }
        $mang=[];
        foreach ($collection as $k=>$value)
        {

            $return[$k] =($value->getCategoryName());

        }
        foreach ($return as $key=>$val)
        {
              $mang[$key]=['value'=>$key,'label'=>$val];
        }
        return $mang;
    }
}