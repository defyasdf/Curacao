<?php
/*******************************************
Magify
This source file is subject to the Magify Software License, which is available at http://magify.com/license/.
Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
If you wish to customize this module for your needs
Please refer to http://www.magentocommerce.com for more information.
@category Magify
@copyright Copyright (C) 2013 Magify (http://magify.com.ua)
*******************************************/

class Magify_SearchIndex_Model_Indexer_Catalog extends Mage_CatalogSearch_Model_Indexer_Fulltext
{
    public function getIndexModel()
    {
        return Mage::getSingleton('searchindex/index_catalog');
    }

    public function getTableName()
    {
        $tablePrefix = Mage::getConfig()->getNode('global/resources/db/table_prefix');
        $table = $tablePrefix.'catalogsearch_fulltext';

        return $table;
    }

    public function getPrimaryKey()
    {
        return 'product_id';
    }

    protected function _getIndexer()
    {
        return Mage::getSingleton('searchindex/catalog_fulltext');
    }

    public function registerEvent(Mage_Index_Model_Event $event)
    {
        return parent::_registerEvent($event);
    }

    public function processEvent(Mage_Index_Model_Event $event)
    {
        return parent::_processEvent($event);
    }

    public function reindexAll()
    {
        Mage::getResourceSingleton('searchindex/catalog_fulltext')->rebuildTable();
        parent::reindexAll();
    }
}