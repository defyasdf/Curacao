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

class Magify_Misspell_Model_Observer
{
    protected $_isFullSearchReindex = false;

    public function onIndexComplete($observer)
    {
        if ($this->_isFullSearchReindex) {
            Mage::getModel('misspell/indexer')->reindexAll();
        }
    }

    public function onIndexStart($observer)
    {
        if ($observer->getData('product_ids') == null) {
            $this->_isFullSearchReindex = true;
        }
    }

    public function onPrepareCollection()
    {
        Mage::helper('catalogsearch')->setSuggestQuery();
    }
}