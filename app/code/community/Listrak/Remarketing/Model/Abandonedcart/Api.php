<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Abandonedcart_Api extends Mage_Api_Model_Resource_Abstract {
	
    public function items($storeId = 1, $startDate = NULL, $endDate = NULL, $perPage, $page) {
    	
		if ($startDate === NULL || !strtotime($startDate)) {
			$this->_fault('incorrect_date');
		}
		
		if ($endDate === NULL || !strtotime($endDate)) {
			$this->_fault('incorrect_date');
		}
		
		$storeIdArray = explode(',', $storeId);
		
		$collection = Mage::getModel('listrak/abandonedcart')
		    ->getCollection()
			->addFieldToFilter('main_table.updated_at',array('from'=>$startDate, 'to'=>$endDate))
			->setPageSize($perPage)->setCurPage($page)
			->addStoreFilter($storeIdArray)
			->setOrder('updated_at', 'ASC');
			
		$result = array();
		
		foreach($collection as $item) {
			$result[] = $item;
		}
		
		return $result;
	}
	
	public function purge($storeId = 1, $endDate = NULL) {
		if ($endDate === NULL || !strtotime($endDate)) {
			$this->_fault('incorrect_date');
		}
		
		$count = 0;
		
		try {
			$rs = Mage::getModel("listrak/session")->getResource();
			$emailTable = $rs->getTable("listrak/session_email");
			$clickTable = $rs->getTable("listrak/click");
			$sessionTable = $rs->getTable("listrak/session");
			
			$delEmailsSql = "DELETE `$emailTable` e FROM `$emailTable` e  INNER JOIN `$sessionTable` s ON s.id = e.session_id WHERE s.updated_at < '$endDate'";
			$delClicksSql = "DELETE `$clickTable` c FROM `$clickTable` c  INNER JOIN `$sessionTable` s ON s.id = c.session_id WHERE s.updated_at < '$endDate'";
			
			$ra = Mage::getSingleton('core/resource')->getConnection('core_write');
			
			$ra->raw_query($delEmailsSql);
			$ra->raw_query($delClicksSql);
			$count = $ra->delete($sessionTable, "updated_at < '$endDate'");
		}
		catch(Exception $ex) {
			Mage::getModel("listrak/log")->addException($ex);
		}
		
		return $count;
	}
}
