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

class Magify_SearchIndex_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getSearchEngine()
    {
        $engine = null;

        if (Mage::helper('core')->isModuleEnabled('Magify_SearchSphinx')) {
            $engine = Mage::helper('searchsphinx')->getEngine();
        } elseif (Mage::helper('core')->isModuleEnabled('Magify_SearchShared')) {
            $engine = Mage::getSingleton('searchshared/engine_fulltext');
        }

        return $engine;
    }

    public function prepareString($string)
    {
        $string = strip_tags($string);
        $string = str_replace('|', ' ', $string);
        $string = ' '.$string.' ';

        return $string;
    }
}