<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Mysql4_Click_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    
    protected function _construct()
    {
        $this->_init('listrak/click');
    }
	
	public function addStoreFilter($storeId)
    {
    	$this->getSelect()
			->join(array('s'=>$this->getTable('listrak/session')), 'main_table.session_id = s.id', array('store_id', 'session_id as session_uid', 'pi_id'))
			->where('s.store_id IN (?)',$storeId);
		return $this;
	}
}
