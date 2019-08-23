<?php


namespace SmartOSC\Blog\Plugin\Model;


class Quote
{

    public function afterIsVirtual(\Magento\Quote\Model\Quote $subject, $result)
    {
        if ($result == false) {
            $isBlogs = true;
            $countItems = 0;
            foreach ($subject->getItemsCollection() as $_item) {
                /* @var $_item \Magento\Quote\Model\Quote\Item */
                if ($_item->isDeleted() || $_item->getParentItemId()) {
                    continue;
                }
                $countItems++;
                if ($_item->getProduct()->getTypeId() != 'simple') {
                    $isBlogs = false;
                    break;
                }
            }
            return $countItems == 0 ? false : $isBlogs;
        }

        return $result;
    }
}