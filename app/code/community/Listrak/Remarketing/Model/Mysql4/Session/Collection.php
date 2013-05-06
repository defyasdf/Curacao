<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Mysql4_Session_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
	
	protected function _construct()
    {
        $this->_init('listrak/session');
    }
	
	public function addStoreFilter($storeIds)
    {
        $this->getSelect()->where('main_table.store_id IN (?)', $storeIds);
        return $this;
    }
	
	protected function _afterLoad() {
		foreach($this->_items as $i) {
			$i->afterLoad();
		}
		
		return parent::_afterLoad();
	}
}
