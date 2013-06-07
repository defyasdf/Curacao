<?php

	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_magento';
	
	$link = mysql_connect($server,$user,$pass);
	mysql_select_db($db,$link) or die("No DB");	
	
	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('admin'); 
	
	$sql = "SELECT * FROM `sales_flat_order` WHERE `created_at` >= '2013-06-05'";
	$result = mysql_query($sql,$link);
		
	while($row = mysql_fetch_array($result)){
		//echo $order->getPayment().'<br>';
		//echo $order->getIncrement_id()."<br />";
	
		$getorder = new Mage_Sales_Model_Order();
	
		$getorder->loadByIncrementId($row['increment_id']);
		
		$payment = $getorder->getPayment()->getMethodInstance()->getTitle();
		if($payment == "No Payment Information Required"){
			if($row['customer_balance_amount']>0){
				$payment = "Pay Using Store Credit";
			}
		}
	//	$shippingAddress = Mage::getModel('sales/order_address')->load($getorder->getShippingAddressId());
	//	$address = $shippingAddress->getData();
		
		if($row['store_id']==1){
			$store = 'English';
		}else{
			$store = 'Spanish';
		}
		$grand_total = $row['grand_total'];
		$dp = $grand_total - $row['curacaocustomerdiscount'];

		$data[] = array("Order_id"=>$row['entity_id'],"Order Number"=>$row['increment_id'],"Custmer_number"=>$row['curacaocustomernumber'],'AR_Estimate'=>$row['estimatenumber'], "Billing_Name"=>$row['customer_firstname'].' '.$row['customer_lastname'] ,"State"=>$row['state'], "Status"=>$row['status'], "Store"=>$store, "Order_date"=>$row['created_at'],"Units"=>$row['total_qty_ordered'],'Subtotal'=>$row['subtotal'],'Shipping'=>$row['shipping_amount'],'Tax'=>$row['tax_amount'],'Discount_Amount'=>$row['discount_amount'],'Customer_account_balance'=>$row['customer_balance_amount'], 'Grand_Total'=>$row['grand_total'],'Payment'=>$payment,"Downpayment"=>$dp,"Curacao_credit_payment"=>$row['curacaocustomerdiscount']);
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
