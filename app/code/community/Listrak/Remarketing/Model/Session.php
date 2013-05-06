<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Session extends Mage_Core_Model_Abstract {
	
	public function _construct()
    {
    	parent::_construct();
        $this->_init('listrak/session');
    }
	
	public function init() {
		$ltksid = Mage::getModel('core/cookie')->get('ltksid');
		$piid = Mage::getModel('core/cookie')->get('personalmerchant');
		$cust_session = Mage::getSingleton("customer/session");
		$ltkpk = 0;
		
		if(!empty($ltksid) && strlen($ltksid) > 37) {
			$ltkpk = intval(substr($ltksid, 37), 10);
			//$this->setSessionId($ltksid);
			$this->load($ltkpk);
			if($this->getSessionId() !== substr($ltksid, 0, 36)) {
				$this->setData(array());
			}
			//$this->getResource()->loadBySessionId($this);
		}
		
		if(!empty($piid)) {
			$this->setPiId($piid);
		}
		
		if(!$this->getId()) {
			$this->setSessionId(Mage::helper('remarketing')->gen_uuid());
			$this->setCreatedAt(gmdate('Y-m-d H:i:s'));
			$this->setIsNew(true);
		}
		
		if($cust_session->isLoggedIn()) {
			$this->setCustomerId($cust_session->getId());
		}
		
		$quoteId = Mage::helper('checkout/cart')->getQuote()->getId();
		
		if($quoteId)
			$this->setQuoteId($quoteId);
		
		$this->setStoreId(Mage::app()->getStore()->getStoreId());
		$this->setUpdatedAt(gmdate('Y-m-d H:i:s'));
		
		if(strlen($this->getIps()) > 0) {
			if(strpos($this->getIps(), $_SERVER["REMOTE_ADDR"]) === FALSE)
				$this->setIps($this->getIps() . "," . $_SERVER["REMOTE_ADDR"]);
		}
		else {
			$this->setIps($_SERVER["REMOTE_ADDR"]);
		}
		
		$this->save();
		
		if($this->getIsNew() === true) {
			Mage::getModel('core/cookie')->set('ltksid', $this->getSessionId() . '-' . $this->getId(), TRUE, NULL, NULL, NULL, FALSE);
		}
		
		$cs	= Mage::getSingleton('core/session');
		
		if($cs->getIsListrakOrderMade()) {
			Mage::getModel('core/cookie')->delete('ltksid');
			$cs->setIsListrakOrderMade(false);
		}
		
		return $this;
	}
	
	public function loadEmails() {
		$this->getResource()->loadEmails($this);
	}
	
	public function delete() {
		$del = $this->getResource()->deleteEmails($this->getId());
		parent::delete();
	}
}
