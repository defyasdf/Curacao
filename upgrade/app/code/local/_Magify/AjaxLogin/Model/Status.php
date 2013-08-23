<?php
/**
 * Magify
 *
 * @category    model
 * @package     magify_ajaxlogin
 * @copyright   Copyright (c) 2012 Magify Inc. (http://www.magify.com)
 * @version		1.0
 * @author		Magify (info@magify.com)
 */

class Magify_AjaxLogin_Model_Status extends Varien_Object
{
    const STATUS_ENABLED	= 1;
    const STATUS_DISABLED	= 2;

    static public function getOptionArray()
    {
        return array(
			self::STATUS_ENABLED    => Mage::helper('ajaxlogin')->__('Enabled'),
			self::STATUS_DISABLED   => Mage::helper('ajaxlogin')->__('Disabled')
        );
    }
}