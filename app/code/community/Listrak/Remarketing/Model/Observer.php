<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Observer {
	
	public function checkForClick($observer) {
		try {
			$click = Mage::getModel('listrak/click');
			$click->checkForClick();
		}
		catch(Exception $ex) {
			Mage::getModel("listrak/log")->addException($ex);
		}
		
		return $this;
	}
	
	public function sessionInit($observer) {
		try {
			$session = Mage::getSingleton('listrak/session');
			$session->init();
		}
		catch(Exception $ex) {
			Mage::getModel("listrak/log")->addException($ex);
		}
		
		return $this;
	}
	
	public function orderPlaced($observer) {
		try {
			$cs	= Mage::getSingleton('core/session');
			$cs->setIsListrakOrderMade(true);
			$session = Mage::getSingleton('listrak/session');
			$session->init();
		}
		catch(Exception $ex) {
			Mage::getModel("listrak/log")->addException($ex);
		}
		
		return $this;
	}
	
	public function subscriberSaved($observer) {
		try {
			$s = $observer->getSubscriber();
			$su = Mage::getModel("listrak/subscriberupdate")->load($s->getSubscriberId(), 'subscriber_id');
			
			if(!$su->getData()) {
				$su->setSubscriberId($s->getSubscriberId());
			}
	
			$su->setUpdatedAt(gmdate('Y-m-d H:i:s'));
			$su->save();
		}
		catch(Exception $ex) {
			Mage::getModel("listrak/log")->addException($ex);
		}
		
		return $this;
	}
	
	public function adminPageLoad($observer) {
		try {
			if(Mage::getSingleton('admin/session')->isFirstPageAfterLogin() && !Mage::helper('remarketing')->checkSetupStatus()) {
				$ae = Mage::getModel("listrak/apiextension_api");
				$sc = $ae->subscribersPurge(gmdate("Y-m-d H:i:s", time() - 604800));
				Mage::getModel("listrak/log")->addMessage("Purged $sc subscriber update entries.");
				
		    	foreach (Mage::app()->getStores() as $storeId => $store) {
		    		$msg = "";
		    		$ab = Mage::getModel("listrak/abandonedcart_api");
					$cl = Mage::getModel("listrak/click_api");
					$l = Mage::getModel("listrak/log_api");
					
					$msg .= "Purged " . $ab->purge($storeId, gmdate("Y-m-d H:i:s", time() - 604800)) . " abandoned carts/sessions\n";
					$msg .= "Purged " . $cl->purge($storeId, gmdate("Y-m-d H:i:s", time() - 604800)) . " clicks\n";
					$msg .= "Purged " . $l->purge($storeId, gmdate("Y-m-d H:i:s", time() - 604800)) . " log entries";
					Mage::getModel("listrak/log")->addMessage($msg, $storeId);
		    	}
			}
		}
		catch(Exception $ex) {
			Mage::getModel("listrak/log")->addException($ex);
		}
		
		return $this;
	}
}
