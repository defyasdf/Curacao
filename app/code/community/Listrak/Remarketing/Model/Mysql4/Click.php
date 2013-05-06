<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Mysql4_Click extends Mage_Core_Model_Mysql4_Abstract {
	
    protected $_read;

    protected $_write;
	
	protected function _construct()
    {
        $this->_init('listrak/click', 'click_id');
		$this->_read = $this->_getReadAdapter();
        $this->_write = $this->_getWriteAdapter();
    }
	
	public function loadByQuoteId($quoteId) {
		$select = $this->_read->select()
            ->from($this->getTable('listrak/session'))
            ->where('quote_id=?',$quoteId)
			->join(array('c'=>$this->getTable('listrak/click')), 
				'id = c.session_id', 
				array());

        if($result = $this->_read->fetchAll($select)) {
        	return $result;
        }
		
		return array();
	}
	
	public function loadLatestByQuoteId($quoteId) {
		$select = $this->_read->select()
            ->from(array('c'=>$this->getTable('listrak/click')))
			->joinInner(array('s'=>$this->getTable('listrak/session')), 's.id = c.session_id', array())
			->where('s.quote_id = ?', $quoteId)
			->order('click_id', 'desc')
			->limit(0,1);

        if($result = $this->_read->fetchRow($select)) {
        	return $result;
        }
		else {
			return NULL;
		}
	}

	public function loadLatestBySessionId($sid) {
		$select = $this->_read->select()
            ->from(array('c'=>$this->getTable('listrak/click')))
			->joinInner(array('s'=>$this->getTable('listrak/session')), 's.id = c.session_id', array())
			->where('s.session_id = ?', $sid)
			->order('click_id', 'desc')
			->limit(0,1);

        if($result = $this->_read->fetchRow($select)) {
        	return $result;
        }
		else {
			return NULL;
		}
	}
}
