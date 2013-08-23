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
class Magify_SearchSphinx_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getEngine()
    {
        $engine = Mage::getSingleton('searchsphinx/engine_fulltext');

        if (Mage::getStoreConfig('searchsphinx/manage/search_engine') == 'sphinx') {
            $isSphinxRunning = false;
            try {
                $isSphinxRunning = Mage::getSingleton('searchsphinx/engine_sphinx')->isSearchdRunning();
            } catch(Exception $e) {
                Mage::logException($e);
            }

            if ($isSphinxRunning) {
                $engine = Mage::getSingleton('searchsphinx/engine_sphinx');
            }
        }

        return $engine;
    }
}