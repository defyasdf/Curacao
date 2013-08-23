<?php
/*******************************************
Magify
This source file is subject to the Magify Software License, which is available at http://magify.com/license/.
Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
If you wish to customize this module for your needs
Please refer to http://www.magentocommerce.com for more information.
@category Magify
@copyright Copyright (C) 2013 Magify (http://magify.com.ua)
*******************************************/


class Magify_MstCore_Model_Notification extends Mage_Core_Model_Abstract
{
    public function check($e) {
        $section = Mage::app()->getRequest()->getParam('section');
        if ($helper = Mage::helper('mstcore/code')->getCodeHelper2($section)) {
            $helper->checkConfig();
        }
    }
}