<?php


namespace SmartOSC\Blog\Model\Config\Source;


use SmartOSC\Blog\Model\Config\Source\CategoryTree;

class CategoryPath extends CategoryTree
{

    protected function _getOptions($itemId = 0)
    {
        $childs =  $this->_getChilds();
        $options = [];

        if (!$itemId) {
            $options[] = [
                'label' => '',
                'value' => 0,
            ];
        }

        if (isset($childs[$itemId])) {
            foreach ($childs[$itemId] as $item) {
                $data = [
                    'label' => $item->getCategoryName() .
                        ($item->getStatus() ? '' : ' ('.__('Disabled').')'),
                    'value' => ($item->getCategoryPath() ? $item->getCategoryPath().'/' : '') . $item->getId(),
                ];
                if (isset($childs[$item->getId()])) {
                    $data['optgroup'] = $this->_getOptions($item->getId());
                }

                $options[] = $data;
            }
        }

        return $options;
    }
}