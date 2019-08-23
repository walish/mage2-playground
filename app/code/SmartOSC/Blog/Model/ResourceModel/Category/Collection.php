<?php


namespace SmartOSC\Blog\Model\ResourceModel\Category;


use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    protected $_idFieldName = \SmartoSC\Blog\Model\Category::CATEGORY_ID;

    protected $fromRoot = true;

    protected function _construct()
    {
        $this->_init('SmartOSC\Blog\Model\Category', 'SmartOSC\Blog\Model\ResourceModel\Category');
        $this->_idFieldName = 'category_id';
    }

    public function addNameToSelect()
    {
        return $this->addAttributeToSelect(['name']);
    }

    public function addVisibilityFilter()
    {
        $this->addAttributeToFilter('status', 1);

        return $this;
    }

    public function addRootFilter()
    {
        $this->addFieldToFilter('parent_id', 0);

        return $this;
    }

    public function getTree($parentId = null)
    {
        $list = [];

        if ($parentId == null) {
            $parentId = $this->fromRoot ? 0 : $this->getRootId();
        }

        $collection = clone $this;
        $collection->addFieldToFilter('parent_id', $parentId)
            ->setOrder('position', 'asc');

        foreach ($collection as $item) {
            $list[$item->getId()] = $item;
            if ($item->getChildrenCount()) {
                $items = $this->getTree($item->getId());
                foreach ($items as $child) {
                    $list[$child->getId()] = $child;
                }
            }
        }

        return $list;
    }

    public function getRootId()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        /** @var \SmartOSC\Blog\Helper\Category $helper */
        $helper = $objectManager->get('\SmartOSC\Blog\Helper\Category');

        return $helper->getRootCategory()->getId();
    }

}