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
 * Represent Index model for Magento Catalog
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
class Magify_SearchIndex_Model_Index_Catalog extends Magify_SearchIndex_Model_Index_Abstract
{
    const INDEX_CODE = 'catalog';

    public function getCode()
    {
        return self::INDEX_CODE;
    }

    public function getPrimaryKey()
    {
        return 'product_id';
    }

    /**
     * Catalog always enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * After process search, we save count search resutls to query
     *
     * @return Magify_SearchIndex_Model_Index_Catalog
     */
    protected function _processSearch()
    {
        parent::_processSearch();

        $query  = Mage::helper('catalogsearch')->getQuery();
        $query->setNumResults(count($this->_matchedIds))
            ->setIsProcessed(1)
            ->save();

        return $this;
    }

    public function getCollection()
    {
        $matchedIds = $this->getMatchedIds();
        $collection = Mage::getModel('catalog/product')->getCollection();
        $this->joinMatched($collection);

        return $collection;
    }
}