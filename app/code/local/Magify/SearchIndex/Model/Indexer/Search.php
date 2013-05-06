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

class Magify_SearchIndex_Model_Indexer_Search extends Mage_Index_Model_Indexer_Abstract
{
    protected $_matchedEntities = array(
        Mage_Catalog_Model_Product::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE,
            Mage_Index_Model_Event::TYPE_MASS_ACTION,
            Mage_Index_Model_Event::TYPE_DELETE
        ),
        Mage_Catalog_Model_Resource_Eav_Attribute::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE,
            Mage_Index_Model_Event::TYPE_DELETE,
        ),
        Mage_Core_Model_Store::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE,
            Mage_Index_Model_Event::TYPE_DELETE
        ),
        Mage_Core_Model_Store_Group::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        ),
        Mage_Core_Model_Config_Data::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        ),
        Mage_Catalog_Model_Convert_Adapter_Product::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        ),
        Mage_Catalog_Model_Category::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        ),
        Magify_SearchIndex_Model_Indexer_Cms::ENTITY => array(
            Mage_Index_Model_Event::TYPE_SAVE
        )
    );

    public function getIndexes()
    {
        return Mage::helper('searchindex/index')->getIndexes();
    }

    /**
     * Retrieve Indexer name
     *
     * @return string
     */
    public function getName()
    {
        return Mage::helper('searchindex')->__('Search Index');
    }

    /**
     * Retrieve Indexer description
     *
     * @return string
     */
    public function getDescription()
    {
        return Mage::helper('searchindex')->__('Rebuild search index');
    }

    /**
     * Check if event can be matched by process
     * Overwrote for check is flat catalog product is enabled and specific save
     * attribute, store, store_group
     *
     * @param Mage_Index_Model_Event $event
     * @return bool
     */
    public function matchEvent(Mage_Index_Model_Event $event)
    {
        $result = false;

        foreach ($this->getIndexes() as $index) {
            $indexer       = $index->getIndexer();
            $indexerResult = $indexer->matchEvent($event);

            if ($indexerResult) {
                $result = $indexerResult;
            }
        }

        return $result;
    }

    /**
     * Register data required by process in event object
     *
     * @param Mage_Index_Model_Event $event
     */
    protected function _registerEvent(Mage_Index_Model_Event $event)
    {
        foreach ($this->getIndexes() as $index) {
            $indexer       = $index->getIndexer();
            $indexerResult = $indexer->registerEvent($event);
        }
    }

    /**
     * Process event
     *
     * @param Mage_Index_Model_Event $event
     */
    protected function _processEvent(Mage_Index_Model_Event $event)
    {
        foreach ($this->getIndexes() as $index) {
            $indexer = $index->getIndexer();
            $indexer->processEvent($event);
        }
    }

    /**
     * Rebuild all index data
     *
     */
    public function reindexAll()
    {
        foreach ($this->getIndexes() as $index) {
            $indexer = $index->getIndexer();
            $indexer->reindexAll();
        }
    }
}
