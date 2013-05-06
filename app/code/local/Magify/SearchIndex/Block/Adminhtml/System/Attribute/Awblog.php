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
 * Attribute list for AW_Blog
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
class Magify_SearchIndex_Block_Adminhtml_System_Attribute_Awblog extends Magify_SearchIndex_Block_Adminhtml_System_Attribute
{
    /**
     * Retrieve attribute array
     * @return array
     */
    protected function _getAttributes()
    {
        $result = array(
            array(
                'code'  => 'title',
                'label' => Mage::helper('searchindex')->__('Title'),
            ),
            array(
                'code'  => 'short_content',
                'label' => Mage::helper('searchindex')->__('Short Content'),
            ),
            array(
                'code'  => 'post_content',
                'label' => Mage::helper('searchindex')->__('Post Content'),
            ),
            array(
                'code'  => 'tags',
                'label' => Mage::helper('searchindex')->__('Tags'),
            ),
            array(
                'code'  => 'category',
                'label' => Mage::helper('searchindex')->__('Category Name'),
            ),
        );

        return $result;
    }
}