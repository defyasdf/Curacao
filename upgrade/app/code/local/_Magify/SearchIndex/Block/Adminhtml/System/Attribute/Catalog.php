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
 * Attribute list for Mage_Catalog
 *
 * @category Magify
 * @package  Magify_SearchIndex
 */
class Magify_SearchIndex_Block_Adminhtml_System_Attribute_Catalog extends Magify_SearchIndex_Block_Adminhtml_System_Attribute
{
    /**
     * Retrieve attribute array
     * @return array
     */
    protected function _getAttributes()
    {
        $result = array();
        $productAttributeCollection = Mage::getResourceModel('catalog/product_attribute_collection');
        $productAttributeCollection->addIsSearchableFilter();
        foreach ($productAttributeCollection as $attribute) {
            $result[] = array(
                'code'  => $attribute->getAttributeCode(),
                'label' => $attribute->getFrontendLabel(),
            );
        }

        return $result;
    }
}