<?php
class Magify_SearchAutocomplete_Block_Result extends Mage_Catalog_Block_Product_Abstract
{
    protected $_collection = false;

    public function getLayer()
    {
        return Mage::getSingleton('catalogsearch/layer');
    }

    public function getCollection()
    {
        if (!$this->_collection) {
            Mage::dispatchEvent('searchautocomplete_prepare_collection');

            $this->_collection = $this->getLayer()
                ->getProductCollection()
                ->setPageSize(Mage::getStoreConfig('searchautocomplete/general/max_results'));
            $this->_collection->getSelect()->order('relevance desc');

            Mage::helper('catalogsearch')->getQuery()->setNumResults($this->_collection->getSize());
        }

        return $this->_collection;
    }

    public function getSuggestQueries ()
    {
        $collection = Mage::helper('catalogsearch')->getSuggestCollection();
        $query = Mage::helper('catalogsearch')->getQueryText();
        $counter = 0;
        $data = array();
        foreach ($collection as $item) {
            $_data = Mage::helper('searchautocomplete')->toSingleRegister($query, $item->getQueryText());
            if ($item->getQueryText() != $query) {
                $data[] = $_data;
            }
        }

        return $data;
    }

    public function isShowPrice()
    {
        return Mage::getStoreConfig('searchautocomplete/general/show_price');
    }

    public function isShowImage()
    {
        return Mage::getStoreConfig('searchautocomplete/general/show_image');
    }

    public function isShowShortDescription()
    {
        return Mage::getStoreConfig('searchautocomplete/general/show_short_description');
    }

    public function getShortDescriptionLen()
    {
        return Mage::getStoreConfig('searchautocomplete/general/short_description_len');
    }

    public function getImageSize()
    {
        $width = 70;
        $height = 50;
        $size = Mage::getStoreConfig('searchautocomplete/general/image_size');
        $size = explode('x', $size);
        if (isset($size[0]) && intval($size[0]) > 0) {
            $width = intval($size[0]);
        }

        if (isset($size[1]) && intval($size[1]) > 0) {
            $height = intval($size[1]);
        }

        return array($width, $height);
    }

    public function getImageWidth()
    {
        $size = $this->getImageSize();

        return $size[0];
    }

    public function getImageHeight()
    {
        $size = $this->getImageSize();

        return $size[1];
    }
}