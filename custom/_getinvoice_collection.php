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
	
	
	$collection = Mage::getModel('sales/order_invoice')->getCollection();
	foreach ($collection as $order) {
		//echo $order->getCuracaocustomernumber().'<br>';
		if($order->getStore_id()==1){
			$store = 'English';
		}else{
			$store = 'Spanish';
		}
		$data[] = array( "Invoice Number"=>$order->getIncrement_id(),"Order_Id"=>$order->getOrder_id(),"State"=>$order->getState(), "Store"=>$store, "Invoice_date"=>$order->getCreated_at(),"Units"=>$order->getTotal_qty(),'Subtotal'=>$order->getSubtotal(),'Shipping'=>$order->getShipping_amount(),'Tax'=>$order->getTax_amount(),'Discount_Amount'=>$order->getDiscount_amount(), 'Grand_Total'=>$order->getGrand_total());
	}
	
	
	  $filename = "Magento_Order.xls";
	
	  header("Content-Disposition: attachment; filename=\"$filename\"");
	  header("Content-Type: application/vnd.ms-excel");
	
	  $flag = false;
	  foreach($data as $row) {
	
		if(!$flag) {
		  // display field/column names as first row
		  echo implode("\t", array_keys($row)) . "\r\n";
		  $flag = true;
		}
	   // array_walk($row, 'cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	  }
	
	
	exit;	
