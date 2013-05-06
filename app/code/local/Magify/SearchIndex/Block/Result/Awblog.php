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
 * Aheadworks blog post list
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
class Magify_SearchIndex_Block_Result_Awblog extends Magify_SearchIndex_Block_Result_Abstract
{
    protected $_collection = null;

    public function getCollection()
    {
        if ($this->_collection == null) {
            $this->_collection = Mage::helper('searchindex/index')->getIndex('awblog')->getCollection();
        }

        return $this->_collection;
    }

    public function getPostUrl($post)
    {
        return Mage::getSingleton('blog/api')->getPostUrl($post->getId());
    }
}