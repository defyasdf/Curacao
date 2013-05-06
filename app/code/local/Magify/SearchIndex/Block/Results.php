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
 * Results block, handle all content types search results
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
class Magify_SearchIndex_Block_Results extends Mage_CatalogSearch_Block_Result
{
    /**
     * Retrieve all enabled indexes
     * @return array
     */
    public function getIndexes()
    {
        return Mage::helper('searchindex/index')->getIndexes();
    }

    /**
     * Return url to search by specific index
     * @param  Magify_SearchIndex_Model_Index_Abstract $index
     * @return string
     */
    public function getIndexUrl($index)
    {
        return Mage::getUrl('*/*/*', array(
            '_current' => true,
            '_query'   => array('index' => $index->getCode(), 'p' => null)
        ));
    }

    /**
     * Return first index with results greater zero or catalog index
     * @return Magify_SearchIndex_Model_Index_Abstract
     */
    public function getFirstMatchedIndex()
    {
        foreach ($this->getIndexes() as $index) {
            if ($index->getCountResults()) {
                return $index;
            }
        }

        return Mage::helper('searchindex/index')->getIndex('catalog');
    }

    /**
     * Return current index or first matched index
     * @return Magify_SearchIndex_Model_Index_Abstract
     */
    public function getCurrentIndex()
    {
        $indexCode    = $this->getRequest()->getParam('index');
        $currentIndex = Mage::helper('searchindex/index')->getIndex($indexCode);
        if ($indexCode === null || $currentIndex->getCountResults() == 0) {
            $currentIndex = $this->getFirstMatchedIndex();
        }
        return $currentIndex;
    }

    public function getListBlock()
    {
        return $this->getChild('searchindex_result_catalog');
    }

    /**
     * Return current search content
     * @return string
     */
    public function getCurrentContent()
    {
        $currentIndex = $this->getCurrentIndex();

        return $this->getChildHtml('searchindex_result_'.$currentIndex->getCode());
    }
}