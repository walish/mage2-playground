<?php


namespace Smart\Bloger\Block\Helper;


class Selectidproduct extends \Magento\Framework\View\Element\Template implements \Magento\Framework\Option\ArrayInterface
{
    protected $productFactory;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,\Smart\Bloger\Model\ProductFactory $productFactory)
    {
        $this->productFactory = $productFactory;
        parent::__construct($context);
    }

    public function toOptionArray()
    {

        $productFactory = $this->productFactory->create();
        $collection = $productFactory->getCollection();

        $return=[];
        $mang=[];
        foreach ($collection as $k=>$value)
        {
            $return[$k] =($value->getProductName());
        }

        $t=-1;
        foreach ($return as $key=>$val)
        {
            $t++;
            if ($key>=1){
                $mang[$t]=['value'=>$key,'label'=>$val];
            }
            else{
                $mang[$t]=['value'=>0,'label'=>$val];
            }
        }
        return $mang;
    }
}
