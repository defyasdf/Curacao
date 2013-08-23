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
 * Attribute list for Mage_Cms
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
class Magify_SearchIndex_Block_Adminhtml_System_Attribute_Cms extends Magify_SearchIndex_Block_Adminhtml_System_Attribute
{
    /**
     * Retrieve attribute list
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
                'code'  => 'meta_keywords',
                'label' => Mage::helper('searchindex')->__('Meta Keywords'),
            ),
            array(
                'code'  => 'meta_description',
                'label' => Mage::helper('searchindex')->__('Meta Description'),
            ),
            array(
                'code'  => 'content_heading',
                'label' => Mage::helper('searchindex')->__('Content Heading'),
            ),
            array(
                'code'  => 'content',
                'label' => Mage::helper('searchindex')->__('Content'),
            ),
        );


        return $result;
    }
}