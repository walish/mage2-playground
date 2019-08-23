<?php


namespace Smart\Bloger\Block\Helper;


class Getproduct extends \Magento\Framework\View\Element\Template implements \Magento\Framework\Option\ArrayInterface
{
    protected $productFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
//        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productFactory,
        \Smart\Bloger\Model\ProductFactory $productFactory,
        array $data = []
    )
    {
        $this->productFactory = $productFactory;
        parent::__construct($context, $data);
    }

    public function toOptionArray()
    {
        $collection = $this->productFactory->create()->getCollection();
//        $collection->addAttributeToSelect('*');

        $return = [];
        $mang = [];
        foreach ($collection as $k => $value) {
            $return[$k] = ($value->getName());
        }

        $t = -1;
        foreach ($return as $key => $val) {
            $t++;
            if ($key >= 1) {
                $mang[$t] = ['value' => $key, 'label' => $val];
            } else {
                $mang[$t] = ['value' => 0, 'label' => $val];
            }

        }
        $m[]=['value'=>0,'label'=>'Root'];
        $mangnew=(array_merge($m,$mang));

        return $mangnew;
    }
}