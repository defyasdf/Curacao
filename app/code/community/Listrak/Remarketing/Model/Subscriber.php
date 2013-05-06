<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Subscriber extends Mage_Newsletter_Model_Subscriber {
	
    public function sendConfirmationSuccessEmail() {
        if(!Mage::getStoreConfig('remarketing/subscription/signup_success_email')) {
            return $this;
        }
		
        return parent::sendConfirmationSuccessEmail();
    }
	
    public function sendUnsubscriptionEmail() {
        if(!Mage::getStoreConfig('remarketing/subscription/unsubscribe_email')) {
            return $this;
        }
		
        return parent::sendUnsubscriptionEmail();
    }
}