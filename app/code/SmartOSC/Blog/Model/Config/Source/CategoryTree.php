<?php


namespace SmartOSC\Blog\Model\Config\Source;


use Magento\Framework\Option\ArrayInterface;
use SmartOSC\Blog\Api\Data\CategoryInterface;
use SmartOSC\Blog\Api\Repository\CategoryRepositoryInterface;

class CategoryTree implements ArrayInterface
{

    protected $_categoryCollectionFactory;

    private $categoryRepository;

    /**
     * @var array
     */
    protected $_options;

    /**
     * @var array
     */
    protected $_childs;

    /**
     * Initialize dependencies.
     *
     * @param \SmartOSC\Blog\Model\ResourceModel\Category\CollectionFactory $authorCollectionFactory
     * @param void
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->categoryRepository->getCollection();
        $rootId = $collection->getRootId();

        return [$this->getOptions($rootId)];
    }

    private function getOptions($parentId)
    {
        $category = $this->categoryRepository->get($parentId);

        $data = [
            'label' => $category->getName(),
            'value' => $category->getId(),
        ];

        $collection = $this->categoryRepository->getCollection()
            ->addFieldToFilter(CategoryInterface::PARENT_ID, $category->getId());

        foreach ($collection as $item) {
            $data['optgroup'][] = $this->getOptions($item->getId());
        }

        return $data;
    }

//    protected function _getOptions($parentId)
//    {
//        $childs =  $this->_getChilds();
//        $options = [];
//
//        if (isset($childs[$itemId])) {
//            foreach ($childs[$itemId] as $item) {
//                $data = [
//                    'label' => $item->getCategoryName() .
//                        ($item->getStatus() ? '' : ' ('.__('Disabled').')'),
//                    'value' => $item->getId(),
//                ];
//                if (isset($childs[$item->getId()])) {
//                    $data['optgroup'] = $this->_getOptions($item->getId());
//                }
//
//                $options[] = $data;
//            }
//        }
//
//        return $options;
//    }

    protected function _getChilds()
    {
        if ($this->_childs === null) {
            $this->_childs =  $this->_categoryCollectionFactory->create()
                ->getGroupedChilds();
        }
        return $this->_childs;
    }
}