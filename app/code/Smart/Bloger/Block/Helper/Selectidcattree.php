<?php


namespace Smart\Bloger\Block\Helper;

class Selectidcattree extends \Magento\Framework\View\Element\Template implements \Magento\Framework\Option\ArrayInterface
{
    private $categoryRepository;

    protected $categoryFactory;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context,\Smart\Bloger\Model\CategoryFactory $categoryFactory)
    {
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $category = $this->categoryFactory->create();
        $collection = $category->getCollection();
        $rootId = $collection->getCategoryId();
        var_dump($collection);die;

        return [$this->getOptions($rootId)];
    }

    /**
     * @param int $parentId
     * @return array
     */
//    private function getOptions($parentId)
//    {
//        $category = $this->categoryRepository->get($parentId);
//
//        $data = [
//            'label' => $category->getName(),
//            'value' => $category->getId(),
//        ];
//
//        $collection = $this->categoryRepository->getCollection()
//            ->addFieldToFilter(CategoryInterface::PARENT_ID, $category->getId())
//            ->setOrder(CategoryInterface::POSITION, 'asc');
//
//        /** @var CategoryInterface $item */
//        foreach ($collection as $item) {
//            $data['optgroup'][] = $this->getOptions($item->getId());
//        }
//
//        return $data;
//    }
}
