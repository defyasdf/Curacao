<?php

class Magify_AjaxSearch_Model_Mysql4_Collection
    extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
{
    public function getNewCollection($query)
    {
        /* @var $collection Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection */
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($this);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($this);
        $productstoshow = Mage::getStoreConfig('ajax_search/general/productstoshow');
        $attributesarr = array();
        $attributes = array('name');
        $searchattr = Mage::getStoreConfig('ajax_search/general/searchattr');
        if ($searchattr != '') {
            $attributes = explode(',', $searchattr);
        }
        $query_array= explode(' ', trim($query));

        $likeStmt = '';
        foreach ($query_array as $query_word){
            $likeStmt .= '#attr# LIKE %' . $query_word . '% AND ';
        }

        $likeStmt = substr($likeStmt, 0, -strlen(' AND '));
        $andWhere = array();
            foreach ($attributes as $attribute) {

                $this->addAttributeToSelect($attribute, true);
                    foreach ($query_array as $query_word) {
                        $andWhere[] = $this->_getAttributeConditionSql(
                            $attribute,
                            array('like' => '%' . $query_word . '%')
                        );
                    }
                $this->getSelect()->orWhere(implode(' AND ', $andWhere));
                $andWhere = array();
           }
        $this
            ->addAttributeToSort(Mage::getStoreConfig('ajax_search/general/sortby'), Mage::getStoreConfig('ajax_search/general/sortorder'))
            ->addUrlRewrite()
            ->setPageSize($productstoshow);

        $this->load();

        return $this;
    }

    public function getNewCategoryCollection($query, $storeId)
    {

        $category = Mage::getModel('catalog/category');
        $collection = $category->getCollection();
        $collection->addAttributeToSelect('*')
            ->addAttributeToSelect('is_active')
            ->setStoreId($storeId);

        $query_array= explode(' ', trim($query));

        foreach ($query_array as $query_word){
            $collection->addFieldToFilter('name',array('like'=> '%' . $query_word .'%'));
        }

        $andWhere = array();
        foreach ($query_array as $query_word) {
            $andWhere[] = $collection->_getAttributeConditionSql(
                'name',
                array('like' => '%' . $query_word . '%')
            );
        }

        $collection->getSelect()->orWhere(implode(' AND ', $andWhere));
        $andWhere = array();

        return $collection;
    }

    public function getNewCmsCollection($query, $storeId)
    {

        $collection = Mage::getModel('cms/page')->getCollection();
        $collection->addStoreFilter($storeId);

        $query_array= explode(' ', trim($query));
        foreach ($query_array as $query_word){
            $collection->addFieldToFilter('title',array('like'=> '%' . $query_word .'%'));
        }

        $andWhere = array();
        foreach ($query_array as $query_word) {
            $andWhere[] = $collection->_getConditionSql(
                'title',
                array('like' => '%' . $query_word . '%')
            );
        }

        $collection->getSelect()->orWhere(implode(' AND ', $andWhere));
        $andWhere = array();

        return $collection;
    }

}