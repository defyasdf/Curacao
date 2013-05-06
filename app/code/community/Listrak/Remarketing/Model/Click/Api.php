<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Click_Api extends Mage_Api_Model_Resource_Abstract {
	
    public function items($storeId = 1, $startDate = NULL, $endDate = NULL, $perPage = 50, $page = 1) {
    	
    	if ($startDate === NULL || !strtotime($startDate)) {
			$this->_fault('incorrect_date');
		}
		
		if ($endDate === NULL || !strtotime($endDate)) {
			$this->_fault('incorrect_date');
		}
		
    	$clicks = Mage::getModel("listrak/click")->getCollection()
			->addFieldToFilter('click_date',array('from'=>$startDate, 'to'=>$endDate))
			->setPageSize($perPage)->setCurPage($page);
			
		$clicks->addStoreFilter($storeId);
		
		$result = array();
		
		foreach($clicks as $item) {
			$result[] = $item;
		}
		
    	return $result;
	}
	
	public function purge($storeId = 1, $endDate) {
		if($endDate === NULL || !strtotime($endDate)) {
			$this->_fault('incorrect_date');
		}
		
		$clicks = Mage::getModel("listrak/click")->getCollection()
			->addFieldToFilter('click_date', array('lt' => $endDate))
			->addStoreFilter($storeId);
			
		$count = $clicks->count();
		
		foreach($clicks as $click){
			$click->delete();
		}

		return $count;
	}
}
