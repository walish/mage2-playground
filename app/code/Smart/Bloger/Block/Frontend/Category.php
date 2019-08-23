<?php


namespace Smart\Bloger\Block\Frontend;


class Category extends \Magento\Framework\View\Element\Template
{
    protected $_postFactory;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,\Smart\Bloger\Model\CategoryFactory $postFactory)
    {
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }

    public function sayHello()
    {
        return __('<h1>Hello tú</h1>');
    }

    public function getPostCollection()
    {

        $post = $this->_postFactory->create();

        $collection = $post->getCollection();

//        //insert data--------------------------------------------------------------
//        $post->setName('tutu');
//        $post->setUrlKey('key key');
//        $post->setTags('hello');
//        $post->setPostContent('toi la tu hahahhahaha');
//        $post->save();
        foreach ($collection as $value){
            echo $value->getCategoryName();
        }

        return $collection;
    }





}