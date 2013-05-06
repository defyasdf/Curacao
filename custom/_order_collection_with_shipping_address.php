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
	
	
	$collection = Mage::getModel('sales/order')->getCollection();
	foreach ($collection as $order) {
		//echo $order->getPayment().'<br>';
		
		$getorder = new Mage_Sales_Model_Order();
	
		$getorder->loadByIncrementId($order->getIncrement_id());
		
		$payment = $getorder->getPayment()->getMethodInstance()->getTitle();
		$shippingAddress = Mage::getModel('sales/order_address')->load($getorder->getShippingAddressId());
		$address = $shippingAddress->getData();
		
		if($order->getStore_id()==1){
			$store = 'English';
		}else{
			$store = 'Spanish';
		}
		
		$breaks = array("\r\n", "\n", "\r");
		$street = str_replace($breaks, " ", $address['street']);
	
		//$street = mysql_real_escape_string($street);
		
		$data[] = array("Order_id"=>$order->getId(),"Order Number"=>$order->getIncrement_id(), "Customer_First_Name"=>$order->getCustomer_firstname(),'customer_last_name'=>$order->getCustomer_lastname() , "Status"=>$order->getStatus(), 'Street'=>$street,'City'=>$address['city'],'State'=>$address['region'],'zip_code'=>$address['postcode'],'Country'=>$address['country_id'],'telephone'=>$address['telephone']);
	}

/*	echo '<pre>';
		print_r($data);
	echo '</pre>';
*/	  $filename = "Magento_Order_with_shipping.xls";
	
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
