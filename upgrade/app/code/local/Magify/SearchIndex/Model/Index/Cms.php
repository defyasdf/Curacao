<?php
class Magify_SearchIndex_Model_Index_Cms extends Magify_SearchIndex_Model_Index_Abstract
{
    const INDEX_CODE = 'cms';

    public function getCode()
    {
        return self::INDEX_CODE;
    }

    public function getPrimaryKey()
    {
        return 'page_id';
    }

    public function getCollection()
    {
        $collection = Mage::getModel('cms/page')->getCollection();
        $collection->addFieldToFilter('is_active', 1);
        $collection->addStoreFilter(Mage::app()->getStore()->getId());

        $this->joinMatched($collection, 'main_table.page_id');

        return $collection;
    }
}