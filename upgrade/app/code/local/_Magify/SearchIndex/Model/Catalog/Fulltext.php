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
 * Catalog search fulltext
 * We overwrite it for change resource class
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
class Magify_SearchIndex_Model_Catalog_Fulltext extends Mage_CatalogSearch_Model_Fulltext
{
    protected function _construct()
    {
        $this->_init('searchindex/catalog_fulltext');
    }
}