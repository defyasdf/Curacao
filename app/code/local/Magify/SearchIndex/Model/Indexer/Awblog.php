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

class Magify_SearchIndex_Model_Indexer_Awblog extends Magify_SearchIndex_Model_Indexer_Abstract
{
    public function getIndexModel()
    {
        return Mage::getSingleton('searchindex/index_awblog');
    }

    public function matchEvent(Mage_Index_Model_Event $event)
    {
        return false;
    }

    public function registerEvent(Mage_Index_Model_Event $event)
    {
        return false;
    }

    public function processEvent(Mage_Index_Model_Event $event)
    {
        $data = $event->getNewData();

        if (!empty($data['catalogsearch_fulltext_reindex_all'])) {
            $this->reindexAll();
        }
    }

    public function rebuildIndex($storeId = null, $postIds = null)
    {
        if (is_null($storeId)) {
            $storeIds = array_keys(Mage::app()->getStores());
            foreach ($storeIds as $storeId) {
                $this->rebuildIndex($storeId, $postIds);
            }
        }

        if (!is_array($postIds) && $postIds != null) {
            $postIds = array($postIds);
        }

        if (!$this->_isTableExists()) {
            return false;
        }

        $table = $this->getIndexTableModel();
        $rows = array();

        $lastEntityId = 0;
        while (true) {
            $collection = $this->_getSearchableEnteties($storeId, $postIds, $lastEntityId);

            if ($collection->count() == 0) {
                break;
            }
            $rows = array();
            foreach ($collection as $post) {
                $data = array($this->getPrimaryKey() => $post->getData($this->getPrimaryKey()));
                $columns = $this->_getColumns(true);
                foreach($columns as $name => $column) {
                    $data[$name] = Mage::helper('searchindex')->prepareString($post->getData($name));
                }
                $lastEntityId     = $post->getId();
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
        $collection = Mage::getModel('blog/post')->getCollection();
        $collection->addStoreFilter($storeId)
            ->addFieldToFilter('status', 1);

        if ($entitIds) {
            $collection->addFieldToFilter('post_id', array('in' => $entitIds));
        }

        $collection->getSelect()->where('main_table.post_id > ?', $lastEntityId)
            ->limit($limit)
            ->order('main_table.post_id');

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