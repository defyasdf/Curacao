<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Block_Adminhtml_Notifications extends Mage_Core_Block_Text
{
    protected function _toHtml()
    {
		$html = "";
		
        if(!Mage::helper('remarketing')->checkSetupStatus()) {
        	$html = "<div class='notification-global'>The Listrak module requires a Listrak account. Please " .
        		"<a href='http://www.listrak.com/partners/magento-extension.aspx'>fill out our form</a> to get an account. ".
        		"If you already have a Listrak account, please contact your account manager " .
        		"or <a href='mailto:support@listrak.com'>support@listrak.com</a>.</div>";
        }
		
        return $html;
    }
}
