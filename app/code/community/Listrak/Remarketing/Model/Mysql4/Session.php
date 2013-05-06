<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Mysql4_Session extends Mage_Core_Model_Mysql4_Abstract {
	
    protected $_read;

    protected $_write;
	
	protected function _construct()
    {
        $this->_init('listrak/session', 'id');
		$this->_read = $this->_getReadAdapter();
        $this->_write = $this->_getWriteAdapter();
    }
	
	protected function _afterLoad(Mage_Core_Model_Abstract $object) {
		if($object->getCustomerId()) {
			$cust = Mage::getModel("customer/customer")->load($object->getCustomerId());
			if($cust) {
				$object->setCustomer($cust);
			}
		}
		
		if($object->getId()) {
			$this->loadEmails($object);
			$this->loadClicks($object);
		}
		return parent::_afterLoad($object);
	}
	
	public function loadBySessionId(Listrak_Remarketing_Model_Session $session) {
		$select = $this->_read->select()
            ->from($this->getTable('listrak/session'))
            ->where('session_id=?',$session->getSessionId());

        if($result = $this->_read->fetchRow($select)) {
        	$session->addData($result);
			$session->loadEmails();
        }
	}
	
	public function loadByQuoteId(Listrak_Remarketing_Model_Session $session) {
		$select = $this->_read->select()
            ->from($this->getTable('listrak/session'))
            ->where('quote_id=?',$session->getQuoteId());

        if($result = $this->_read->fetchRow($select)) {
        	$session->addData($result);
			$session->loadEmails();
        }
	}
	
	public function insertEmail(Listrak_Remarketing_Model_Session $session, $email, $emailcaptureId) {
		if($session->getId()) {
			$data = array();
	        $data['session_id'] = $session->getId();
	        $data['email'] = $email;
			$data['emailcapture_id'] = $emailcaptureId;
			$data['created_at'] = gmdate('Y-m-d H:i:s');
	        $this->_write->insert($this->getTable('listrak/session_email'), $data);
		}
	}
	
	public function loadEmails(Listrak_Remarketing_Model_Session $session) {
		$select = $this->_read->select()
	            ->from(array('se'=>$this->getTable('listrak/session_email')))
				->joinLeft(array('ec'=>$this->getTable('listrak/emailcapture')), 
					'se.emailcapture_id = ec.emailcapture_id', 
					array('*'))
	            ->where('session_id=?',$session->getId());
			
		$emails = $this->_read->fetchAll($select);
		$session->setEmails($emails);
	}
	
	public function loadClicks(Listrak_Remarketing_Model_Session $session) {
		$clicks = Mage::getModel("listrak/click")->getCollection()
			->addFieldToFilter('session_id',array('eq'=>$session->getId()));
		
		$sessionClicks = array();
		
		foreach($clicks as $click) {
			$sessionClicks[] = $click;
		}
		
		$session->setClicks($sessionClicks);
	}
	
	public function deleteEmails($id) {
		$del = $this->_write->delete($this->getTable("listrak/session_email"), "session_id = $id");
	}
}
