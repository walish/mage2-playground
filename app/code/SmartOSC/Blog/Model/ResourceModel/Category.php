<?php


namespace SmartOSC\Blog\Model\ResourceModel;

use Magento\Eav\Model\Entity\AbstractEntity;
use Magento\Eav\Model\Entity\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;
use Magento\Framework\Filter\FilterManager;

use Magento\Framework\Model\AbstractModel;

class Category extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

//    protected function _afterSave(DataObject $object)
//    {
//        /** @var \SmartOSC\Blog\Model\Category $object */
//
//        if (substr($object->getPath(), -1) == '/' || !$object->getPath()) {
//            $object->setPath($object->getPath() . $object->getId());
//            $this->savePath($object);
//        }
//
//        if ($object->dataHasChangedFor('parent_id')) {
//            $newParent = \Magento\Framework\App\ObjectManager::getInstance()
//                ->create('SmartOSC\Blog\Model\Category')
//                ->load($object->getParentId());
//            $this->changeParent($object, $newParent);
//        }
//
//        return parent::_afterSave($object);
//    }

    protected function _beforeSave(AbstractModel $category)
    {
        /* @var \SmartOSC\Blog\Model\Category $category */

        parent::_beforeSave($category);

//        if (!$category->getChildrenCount()) {
//            $category->setChildrenCount(0);
//        }
//
//        if ($category->isObjectNew()) {
//            if (!$category->hasParentId()) {
//                $category->setParentId(1);
//
//            }
//            /*
//             * Get Object Parent
//             * @var  \SmartOSC\Blog\Model\Category $parent
//            */
//
//            $parent = ObjectManager::getInstance()
//                ->create('SmartOSC\Blog\Model\Category')
//                ->load($category->getParentId());
//
//            $category->setPath($parent->getPath());
//
////            if ($category->getPosition() === null) {
////                $category->setPosition($this->getMaxPosition($category->getPath()) + 1);
////            }
//
//
//            $path = explode('/', $category->getPath());
//            print_r($path);die();
//            $level = count($path) - ($category->getId() ? 1 : 0);
//            $toUpdateChild = array_diff($path, [$category->getId()]);
//
//            if (!$category->hasPosition()) {
//                $category->setPosition($this->getMaxPosition(implode('/', $toUpdateChild)) + 1);
//            }
//
//            if (!$category->hasLevel()) {
//                $category->setLevel($level);
//            }
//
//            if (!$category->getId() && $category->getPath()) {
//                $category->setPath($category->getPath() . '/');
//            }
//
//            $this->getConnection()->update(
//                $this->getEntityTable(),
//                ['children_count' => new \Zend_Db_Expr('children_count+1')],
//                ['entity_id IN(?)' => $toUpdateChild]
//            );
//        }

        return $this;
    }

    protected function getMaxPosition($path)
    {
        $connection    = $this->getConnection();
        $positionField = $connection->quoteIdentifier('position');
        $level         = count(explode('/', $path));
        $bind          = ['c_level' => $level, 'c_path' => $path . '/%'];
        $select        = $connection->select()->from(
            $this->getTable('smartosc_categories'),
            'MAX(' . $positionField . ')'
        )->where(
            $connection->quoteIdentifier('path') . ' LIKE :c_path'
        )->where($connection->quoteIdentifier('level') . ' = :c_level');

        $position = $connection->fetchOne($select, $bind);
        if (!$position) {
            $position = 0;
        }

        return $position;
    }
    protected function _construct()
    {
        $this->_init('smartosc_categories', 'category_id');
        $this->_categoryTable = $this->getTable('smartosc_categories');
        $this->_blogCategoryTable = $this->getTable('smartosc_blog_categories');
    }
}