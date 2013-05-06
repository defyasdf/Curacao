<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Log extends Mage_Core_Model_Abstract
{
	const LOGTYPE_MESSAGE = 1;
	const LOGTYPE_EXCEPTION = 2;
	
    public function _construct()
    {
        parent::_construct();
		$this->_init('listrak/log');
    }
	
	public function addMessage($msg, $storeId = NULL) {
		if($storeId == NULL) {
			$storeId = Mage::app()->getStore()->getStoreId();
		}
		$this->setMessage($msg);
		$this->setLogTypeId(self::LOGTYPE_MESSAGE);
		$this->setStoreId($storeId);
		$this->save();
	}
	
	public function addException($msg, $storeId = NULL) {
		if($storeId == NULL) {
			$storeId = Mage::app()->getStore()->getStoreId();
		}
		$this->setMessage($msg);
		$this->setLogTypeId(self::LOGTYPE_EXCEPTION);
		$this->setStoreId($storeId);
		$this->save();
	}
}