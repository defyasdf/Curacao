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

class Magify_SearchIndex_Helper_Index extends Mage_Core_Helper_Abstract
{
    protected $_indexes = array(
        'catalog',
        'cms',
        'awblog',
        'maction'
    );

    public function getIndexes()
    {
        $indexes = array();
        foreach ($this->_indexes as $indexCode){
            $index = Mage::getSingleton('searchindex/index_'.$indexCode);
            if ($index->isEnabled()) {
                $indexes[$indexCode] = $index;
            }
        }

        return $indexes;
    }

    public function getIndex($index)
    {
        $indexes = $this->getIndexes();
        if (isset($indexes[$index])) {
            return $indexes[$index];
        }

        return false;
    }
}