<?php
	
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	
	$mageFilename = '../app/Mage.php';
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	$myOrder=Mage::getModel('sales/order'); 
	$orders=Mage::getModel('sales/mysql4_order_collection');
	
	//Optional filters you might want to use - more available operations in method _getConditionSql in Varien_Data_Collection_Db. 
//	$orders->addFieldToFilter('total_paid',Array('gt'=>0)); //Amount paid larger than 0
//	$orders->addFieldToFilter('status',Array('eq'=>"processing"));  //Status is "processing"
	
	$allIds=$orders->getAllIds();
	//echo count($allIds);
	//echo '<br>';
	
	foreach($allIds as $thisId) {
		$myOrder->load($thisId);
		
		$order = Mage::getModel('sales/order')->load($thisId);
		$items = $order->getAllItems();
		$itemcount=count($items);
		$name=array();
		$unitPrice=array();
		$sku=array();
		$ids=array();
		$qty=array();
		$itemdetail ='';
		foreach ($items as $itemId => $item){

			$name[] = $item->getName();
			$unitPrice[] = $item->getPrice();	
			$sku[] = $item->getSku();
			$qty[] = $item->getQtyToInvoice();
			
			 $itemdetail .= '"'.$item->getSku().":".$item->getPrice().":".(int)$item->getQtyOrdered()."',";
		}
		
		
//		print_r()
		
		//Some random fields
	/*	echo "'" . $myOrder->getBillingAddress()->getLastname() . "',";
		echo "'" . $myOrder->getTotal_paid() . "',";
		echo "'" . $myOrder->getShippingAddress()->getTelephone() . "',";
		echo "'" . $myOrder->getPayment()->getCc_type() . "',";
		echo "'" . $myOrder->getPayment()->getOrder()->getIncrementId() . "',";
		
		echo "'" . $myOrder->getStatus() . "',";
		echo "<br>";*/
		
		$data[] = array("Order_Number"=>$myOrder->getPayment()->getOrder()->getIncrementId(),"Products"=>$itemdetail,"Status"=>$myOrder->getStatus());
		
	}
	
//	echo count($data);
	
	 $filename = "Order_report" . date('Ymd') . ".xls";
	
	  header("Content-Disposition: attachment; filename=\"$filename\"");
	  header("Content-Type: application/vnd.ms-excel");
	
	
	  $flag = false;
	
	  foreach($data as $row) {
	
		//if(!$flag) {
		  // display field/column names as first row
		  //echo implode("\t", array_keys($row)) . "\r\n";
		  //$flag = true;
		//}
	   // array_walk($row, 'cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	  }
	
	  exit;
	
	