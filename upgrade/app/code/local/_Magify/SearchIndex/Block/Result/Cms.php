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
 * Cms pages list
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
class Magify_SearchIndex_Block_Result_Cms extends Magify_SearchIndex_Block_Result_Abstract
{
    protected $_collection = null;

    public function getCollection()
    {
        if ($this->_collection == null) {
            $this->_collection = Mage::helper('searchindex/index')->getIndex('cms')->getCollection();
        }

        return $this->_collection;
    }

    public function getPageContent($page)
    {
        $helper    = Mage::helper('cms');
        $processor = $helper->getPageTemplateProcessor();
        $html      = $processor->filter($page->getContent());

        return $html;
    }
}