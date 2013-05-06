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

class Magify_SearchIndex_Model_Indexer_Cms extends Magify_SearchIndex_Model_Indexer_Abstract
{
    const ENTITY = 'cms';

    public function getIndexModel()
    {
        return Mage::getSingleton('searchindex/index_cms');
    }

    public function matchEvent(Mage_Index_Model_Event $event)
    {
        $entity = $event->getEntity();
        $result = false;

        if ($entity == self::ENTITY) {
            $result = true;
        }

        return $result;
    }

    public function registerEvent(Mage_Index_Model_Event $event)
    {
        if ($event->getEntity() == self::ENTITY) {
            $this->_registerCmsPageEvent($event);
        }
    }

    protected function _registerCmsPageEvent(Mage_Index_Model_Event $event)
    {
        switch ($event->getType()) {
            case Mage_Index_Model_Event::TYPE_SAVE:
                $page = $event->getDataObject();

                $event->addNewData('searchindex_update_page_id', $page->getId());
                break;
            case Mage_Index_Model_Event::TYPE_DELETE:
                $page = $event->getDataObject();

                $event->addNewData('searchindex_delete_page_id', $page->getId());
                break;
        }

        return $this;
    }

    public function processEvent(Mage_Index_Model_Event $event)
    {
        $data = $event->getNewData();

        if (!empty($data['catalogsearch_fulltext_reindex_all'])) {
            $this->reindexAll();
        } else if (!empty($data['searchindex_delete_page_id'])) {
            $pageId = $data['searchindex_delete_page_id'];

            $this->cleanIndex(null, $pageId);
        } else if (!empty($data['searchindex_update_page_id'])) {
            $pageId = $data['searchindex_update_page_id'];
            $pageIds = array($pageId);

            $this->rebuildIndex(null, $pageIds);
        }
    }

    public function rebuildIndex($storeId = null, $pageIds = null)
    {
        if (is_null($storeId)) {
            $storeIds = array_keys(Mage::app()->getStores());
            foreach ($storeIds as $storeId) {
                $this->rebuildIndex($storeId, $pageIds);
            }
        }

        if (!is_array($pageIds) && $pageIds != null) {
            $pageIds = array($pageIds);
        }

        if (!$this->_isTableExists()) {
            return false;
        }

        $table = $this->getIndexTableModel();
        $rows = array();

        $lastEntityId = 0;
        while (true) {
            $collection = $this->_getSearchableEnteties($storeId, $pageIds, $lastEntityId);
            if ($collection->count() == 0) {
                break;
            }
            $rows = array();
            foreach ($collection as $page) {
                $data = array($this->getPrimaryKey() => $page->getData($this->getPrimaryKey()));
                $columns = $this->_getColumns(true);
                foreach($columns as $name => $column) {
                    $data[$name] = Mage::helper('searchindex')->prepareString($page->getData($name));
                }
                $lastEntityId     = $page->getId();
                $data['store_id'] = $storeId;
                $data['updated']  = 1;
                $rows[]           = $data;
            }

            if (count($rows)) {
                $this->_getConnection()->insertOnDuplicate($this->_getTableName(), $rows, array_keys($columns));
            }
        }
    }

    protected function _getSearchableEnteties($storeId, $entitIds = null, $lastEntityId = 0, $limit = 100)
    {
        $collection = Mage::getModel('cms/page')->getCollection();
        $collection->addStoreFilter($storeId)
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('identifier', array('nin' => array('no-route', 'home', 'enable-cookies')));

        if ($entitIds) {
            $collection->addFieldToFilter('page_id', array('in' => $entitIds));
        }

        $collection->getSelect()->where('main_table.page_id > ?', $lastEntityId)
            ->limit($limit)
            ->order('main_table.page_id');

        return $collection;
    }

    public function cleanIndex($storeId = null, $entityId = null)
    {
        $where = array();

        if (!is_null($storeId)) {
            $where[] = $this->_getWriteAdapter()->quoteInto('store_id=?', $storeId);
        }
        if (!is_null($entityId)) {
            $where[] = $this->_getWriteAdapter()->quoteInto($this->getPrimaryKey().' IN (?)', $entityId);
        }

        $this->_getConnection()->delete($this->_getTableName(), $where);

        return $this;
    }

    public function reindexAll()
    {
        if (!$this->_isTableExists()) {
            $this->_createTable();
        } else {
            $this->_dropTable();
            $this->_createTable();
        }

        $this->rebuildIndex();
    }
}