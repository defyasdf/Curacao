<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Click extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
		$this->_init('listrak/click');
    }
	
	public function checkForClick() {
		foreach(Mage::app()->getRequest()->getParams() as $key => $value) {
		    if(stripos($key, 'trk_') !== false) {
			    $this->recordClick();
				break;
			}
		}
	}
	
	private function recordClick() {
		$this->setTokenUid(Mage::helper('remarketing')->gen_uuid());
		$this->setClickDate(gmdate('Y-m-d H:i:s'));
		$session = Mage::getSingleton('listrak/session');
		$session->init();
		$this->setSessionId($session->getId());
		$this->setQuerystring(http_build_query(Mage::app()->getRequest()->getParams()));
		$this->save();
		
		Mage::getModel('core/cookie')->set('ltktrk', $this->getTokenUid(), TRUE, NULL, NULL, NULL, TRUE);
	}
}