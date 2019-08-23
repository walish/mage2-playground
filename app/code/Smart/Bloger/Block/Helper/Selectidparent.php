<?php


namespace Smart\Bloger\Block\Helper;


class Selectidparent extends \Magento\Framework\View\Element\Template implements \Magento\Framework\Option\ArrayInterface
{
    protected $categoryFactory;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,\Smart\Bloger\Model\CategoryFactory $categoryFactory)
    {
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    public function toOptionArray()
    {

        $category = $this->categoryFactory->create();
        $collection = $category->getCollection();

        $return=[];
        $mang=[];
//        for($i=0;$i<count($collection);$i++)
//        {
//            $return[$i] =($collection[$i]->getCategoryName());
//        }
        foreach ($collection as $k=>$value)
        {
            $return[$k] =($value->getCategoryName());
        }

        $t=-1;
        foreach ($return as $key=>$val)
        {
            $t++;
            if ($key>=1){
                $mang[]=['value'=>$key,'label'=>$val];
            }
            else{
                $mang[]=['value'=>0,'label'=>$val];
            }

        }
            $m[]=['value'=>0,'label'=>'Root'];
            $mangnew=(array_merge($m,$mang));
//
//        echo '<pre/>';
//        print_r($return);
//        print_r($mang);die;
        return $mangnew;
    }
}
