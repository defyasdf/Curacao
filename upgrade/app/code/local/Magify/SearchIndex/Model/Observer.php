<?php
/**
 * Magify
 *
 * This source file is subject to the Magify Software License, which is available at http://magify.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category Magify
 * @package Magify_SearchIndex
 * @copyright Copyright (C) 2013 Magify (http://magify.com)
 */

class Magify_SearchIndex_Model_Observer
{
    /**
     * Indexer model
     *
     * @var Mage_Index_Model_Indexer
     */
    protected $_indexer;

    public function __construct()
    {
        $this->_indexer = Mage::getSingleton('index/indexer');
    }

    /**
     * Cms Page after commit observer. Process cms page indexes
     *
     * @param Varien_Event_Observer $observer
     */
    public function processCmsPageSave(Varien_Event_Observer $observer)
    {
        $cmsPage = $observer->getEvent()->getDataObject();
        $this->_indexer->processEntityAction(
            $cmsPage,
            Magify_SearchIndex_Model_Indexer_Cms::ENTITY,
            Mage_Index_Model_Event::TYPE_SAVE
        );
    }

    /**
     * Cms Page after delete observer. Process cms page indexes
     *
     * @param Varien_Event_Observer $observer
     */
    public function processCmsPageDelete(Varien_Event_Observer $observer)
    {
        $cmsPage = $observer->getEvent()->getDataObject();
        $this->_indexer->processEntityAction(
            $cmsPage,
            Magify_SearchIndex_Model_Indexer_Cms::ENTITY,
            Mage_Index_Model_Event::TYPE_DELETE
        );
    }
}