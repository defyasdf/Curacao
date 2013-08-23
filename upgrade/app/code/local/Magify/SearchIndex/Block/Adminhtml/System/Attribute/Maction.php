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
 * Attribute list for M_Action
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
class Magify_SearchIndex_Block_Adminhtml_System_Attribute_Maction extends Magify_SearchIndex_Block_Adminhtml_System_Attribute
{
    /**
     * Retrieve attribute array
     * @return array
     */
    protected function _getAttributes()
    {
        $result = array(
            array(
                'code'  => 'name',
                'label' => Mage::helper('searchindex')->__('Name'),
            ),
            array(
                'code'  => 'short_description',
                'label' => Mage::helper('searchindex')->__('Short Description'),
            ),
            array(
                'code'  => 'full_description',
                'label' => Mage::helper('searchindex')->__('Full Description'),
            ),
        );

        return $result;
    }
}