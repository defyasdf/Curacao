<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Log_Api extends Mage_Api_Model_Resource_Abstract {
	
    public function items($storeId = 1, $startDate = NULL, $endDate = NULL, $perPage = 50, $page = 1, $logTypeId = 0) {
    	
    	if ($startDate === NULL || !strtotime($startDate)) {
			$this->_fault('incorrect_date');
		}
		
		if ($endDate === NULL || !strtotime($endDate)) {
			$this->_fault('incorrect_date');
		}
		
    	$logs = Mage::getModel("listrak/log")->getCollection()
			->addFieldToFilter('date_entered',array('from'=>$startDate, 'to'=>$endDate))
			->setPageSize($perPage)->setCurPage($page)
			->addStoreFilter($storeId);
		
		$result = array();
		
		foreach($logs as $item) {
			$result[] = $item;
		}
		
		return $result;
	}
	
	public function purge($storeId = 1, $endDate = NULL) {
		if($endDate === NULL || !strtotime($endDate)) {
			$this->_fault('incorrect_date');
		}
		
		$logs = Mage::getModel("listrak/log")->getCollection()
			->addFieldToFilter('date_entered', array('lt' => $endDate))
			->addStoreFilter($storeId);
			
		$count = $logs->count();
		
		foreach($logs as $log){
			$log->delete();
		}

		return $count;
	}
	
	public function toggle($storeId = 1, $onOff) {
		return $onOff;
	}
}
