<?php


namespace SmartOSC\Blog\Model\ResourceModel\Blog;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    public function setBlogNameFilter($blogSearch)
    {
        $this->getSelect()->where("blog_name LIKE '%$blogSearch%'");
        return $this;
    }

    protected function _construct()
    {
        $this->_init('SmartOSC\Blog\Model\Blog', 'SmartOSC\Blog\Model\ResourceModel\Blog');
        $this->_idFieldName = 'blog_id';
    }
}