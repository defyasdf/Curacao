<?php
ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('admin'); 
	
	$orders = Mage::getModel('sales/order')
		  ->getCollection()
		  ->addAttributeToFilter('state', array('neq' => Mage_Sales_Model_Order::STATE_CANCELED))
		  ->addAttributeToFilter('increment_id', 100001881)
		  ->getFirstItem();
	
	$comments = $orders->getStatusHistoryCollection();
		// writeStartTag("Notes");
		 foreach( $comments as $hist_cmt )
		 {
			echo $hist_cmt->getComment();
			echo '<br>';
		//	writeFullElement("Note", $hist_cmt->getComment(), array("public" => "false"));
			
			
		 } 		
//		writeCloseTag("Notes");	
	

?>