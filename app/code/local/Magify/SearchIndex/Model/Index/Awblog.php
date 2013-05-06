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
 * Represent Index model for Aheadworks blog
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
class Magify_SearchIndex_Model_Index_Awblog extends Magify_SearchIndex_Model_Index_Abstract
{
    const INDEX_CODE = 'awblog';

    public function getCode()
    {
        return self::INDEX_CODE;
    }

    public function getPrimaryKey()
    {
        return 'post_id';
    }

    public function getCollection()
    {
        $collection = Mage::getModel('blog/post')->getCollection();
        $collection->addFieldToFilter('status', 1);
        $collection->addStoreFilter(Mage::app()->getStore()->getId());

        $this->joinMatched($collection, 'main_table.post_id');

        return $collection;
    }
}