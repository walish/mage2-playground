<?php
//
//
//namespace Smart\Bloger\Observer;
//use Magento\Framework\Event\Observer as EventObserver;
//use Magento\Framework\Data\Tree\Node;
//use Magento\Framework\Event\ObserverInterface;
//
//class Topmenu implements ObserverInterface
//{
////    public function __construct(
////        ...//add dependencies here if needed
////    )
////    {
////        ...
////    }
//    /**
//     * @param EventObserver $observer
//     * @return $this
//     */
//    public function execute(EventObserver $observer)
//    {
//        /** @var \Magento\Framework\Data\Tree\Node $menu */
//        $menu = $observer->getMenu();
//        $tree = $menu->getTree();
//        $data = [
//            'name'      => __('Menu item label here'),
//            'id'        => 'some-unique-id-here',
//            'url'       => 'url goes here',
//            //'is_active' => (expression to determine if menu item is selected or not)
//        ];
//        $node = new Node($data, 'id', $tree, $menu);
//        $menu->addChild($node);
//        return $this;
//    }
//}