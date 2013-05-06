<?php
class Magify_SearchIndex_Model_Layer extends Mage_CatalogSearch_Model_Layer
{
    public function prepareProductCollection($collection)
    {
        $collection
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->setStore(Mage::app()->getStore())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addStoreFilter()
            ->addUrlRewrite();

        $catalogIndex = Mage::helper('searchindex/index')->getIndex('catalog');
        $catalogIndex->joinMatched($collection);
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($collection);


        return $this;
    }
}
