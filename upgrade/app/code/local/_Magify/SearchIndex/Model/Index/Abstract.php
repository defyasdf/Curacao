<?php
/**
 * Magify
 *
 * This source file is subject to the Magify Software License, which is available at http://magify.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Magify
 * @package   Magify_SearchIndex
 * @copyright Copyright (C) 2013 Magify (http://magify.com)
 */


/**
 * Represent Index model
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
abstract class Magify_SearchIndex_Model_Index_Abstract extends Varien_Object
{
    /**
     * Represent array of matched entity ids
     *
     * @var array
     */
    protected $_matchedIds = null;

    /**
     * Is cache enabled for search resutls
     *
     * @var boolean
     */
    protected $_cache = false;
    protected $_tmpTableCreated = false;

    /**
     * Get index identifier
     *
     * @return string
     */
    abstract public function getCode();

    /**
     * Get index primary key (entity primary key)
     *
     * @return string
     */
    abstract public function getPrimaryKey();

    /**
     * Get collection of founded items
     *
     * @return string
     */
    abstract public function getCollection();

    /**
     * Get Indexer Model for current index
     *
     * @return Magify_SearchIndex_Model_Indexer_Abstract
     */
    public function getIndexer()
    {
        return Mage::getSingleton('searchindex/indexer_'.$this->getCode());
    }

    /**
     * Get index title
     *
     * @return string
     */
    public function getTitle()
    {
        return Mage::getStoreConfig('searchindex/'.$this->getCode().'/title');
    }

    /**
     * Is index enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return (bool) Mage::getStoreConfig('searchindex/'.$this->getCode().'/enabled');
    }

    /**
     * Retrieve index attributes with attribute weight
     *
     * @return array
     */
    public function getAttributes()
    {
        $attributes = array();

        $attrConfig = unserialize(Mage::getStoreConfig('searchindex/'.$this->getCode().'/attribute'));
        if (!is_array($attrConfig)) {
            $attrConfig = array();
        }

        foreach ($attrConfig as $attr) {
            $attributes[$attr['attribute']] = intval($attr['value']);
        }

        return $attributes;
    }

    /**
     * Get matched ids array
     *
     * @return array
     */
    public function getMatchedIds()
    {
        if ($this->_matchedIds == null) {
            $this->_processSearch();
        }
        return $this->_matchedIds;
    }

    /**
     * Set matched ids array
     *
     * @return array
     */
    public function setMatchedIds($ids)
    {
        if (!is_array($ids)) {
            $ids = array();
        }

        $this->_matchedIds = $ids;

        return $this;
    }

    /**
     * Get catalog search query model
     *
     * @return Mage_CatalogSearch_Model_Query
     */
    public function getQuery()
    {
        return Mage::helper('catalogsearch')->getQuery();
    }

    /**
     * Process search for current index
     *
     * @todo add alternative engine on exception
     *
     * @return Magify_SearchIndex_Model_Index_Abstract
     */
    protected function _processSearch()
    {
        $engine = Mage::helper('searchindex')->getSearchEngine();
        $query  = Mage::helper('catalogsearch')->getQuery();
        try {
            $result = $engine->query($query->getQueryText(), $query->getStoreId(), $this);
            $this->setMatchedIds($result);
        } catch(Exception $e) {
            Mage::logException($e);
            $this->setMatchedIds(array());
        }

        return $this;
    }

    /**
     * Get count founded items in result collection
     *
     * @return int
     */
    public function getCountResults()
    {
        return $this->getCollection()->getSize();
    }

    /**
     * Join Matched ids with specific collection
     *
     * @todo refactoring
     *
     * @param  Varien_Collection $collection
     * @param  string            $mainTableKeyField
     * @return Magify_SearchIndex_Model_Index_Abstract
     */
    public function joinMatched($collection, $mainTableKeyField = 'e.entity_id')
    {
        $matchedIds = $this->getMatchedIds();
        $this->_createTemporaryTable($matchedIds);

        $collection->getSelect()->joinLeft(
            array('tmp_table' => $this->_getTemporaryTableName()),
            '(tmp_table.entity_id='.$mainTableKeyField.')',
            array('relevance' => 'tmp_table.relevance')
        );
        if ($this->_cache) {
            $collection->getSelect()->where('tmp_table.query_id = '.$this->getQuery()->getId());
        }
        $collection->getSelect()->where('tmp_table.id IS NOT NULL');

        return $this;
    }

    /**
     * Create temporary table if cache disabled
     * And fill table with matched ids
     *
     * @todo refactoring
     *
     * @param  array $matchedIds
     * @return Magify_SearchIndex_Model_Index_Abstract
     */
    protected function _createTemporaryTable($matchedIds)
    {
        if ($this->_tmpTableCreated) {
            return $this;
        }

        $values = array();
        $queryId = $this->getQuery()->getId();

        foreach ($matchedIds as $id => $relevance) {
            $values[] = '('.$queryId.','.$id.','.$relevance.')';
        }

        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');

        $query = '';
        if ($this->_cache) {
            $query .= "CREATE TABLE IF NOT EXISTS `".$this->_getTemporaryTableName()."` (";
        } else {
            $query .= "CREATE TEMPORARY TABLE IF NOT EXISTS `".$this->_getTemporaryTableName()."` (";
        }
        $query .= "
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `query_id` int(11) unsigned NOT NULL,
                `entity_id` int(11) unsigned NOT NULL,
                `relevance` int(11) unsigned NOT NULL,
                PRIMARY KEY (`id`)";
        if ($this->_cache) {
            $query .= ")ENGINE=MEMORY DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            $query .= "DELETE FROM `".$this->_getTemporaryTableName()."` WHERE `query_id`=".$queryId.";";
        } else {
            $query .= ")ENGINE=MYISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
        }
        if (count($values)) {
            $query .= "INSERT INTO `".$this->_getTemporaryTableName()."` (`query_id`, `entity_id`, `relevance`)".
                "VALUES ".implode(',', $values).";";
        }

        $connection->raw_query($query);
        $this->_tmpTableCreated = true;

        return $this;
    }

    /**
     * Get Temporary table name for matched ids
     *
     * @return string
     */
    protected function _getTemporaryTableName()
    {
        return 'searchindex_result_'.$this->getCode();
    }
}