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
	
	$dT = explode('/',$_REQUEST['edate']);
	$dF = explode('/',$_REQUEST['sdate']);
	if(trim($_REQUEST['edate'])!=''){
		$to = $dT[2].'-'.$dT[0].'-'.$dT[1];

		 
	}
	if(trim($_REQUEST['sdate'])!=''){
			$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
	}
	$collection = Mage::getModel('sales/order')->getCollection();
	if(trim($_REQUEST['edate'])!='' && trim($_REQUEST['sdate'])!=''){
	$collection->addAttributeToFilter('created_at', array(
            'from'  => $from,
            'to'    => $to,                    
        )); 
	}
	foreach ($collection as $order) {
		//echo $order->getPayment().'<br>';
		
		$getorder = new Mage_Sales_Model_Order();
	
		$getorder->loadByIncrementId($order->getIncrement_id());
		
		$payment = $getorder->getPayment()->getMethodInstance()->getTitle();
		if($payment == "No Payment Information Required"){
			if($order->getCustomer_balance_amount()>0){
				$payment = "Pay Using Store Credit";
			}
		}
	//	$shippingAddress = Mage::getModel('sales/order_address')->load($getorder->getShippingAddressId());
	//	$address = $shippingAddress->getData();
		
		if($order->getStore_id()==1){
			$store = 'English';
		}else{
			$store = 'Spanish';
		}
		$grandTotal = $order->getGrandTotal();
		$dp = $grandTotal - $order->getCuracaocustomerdiscount();

		$data[] = array("Order_id"=>$order->getId(),"Order Number"=>$order->getIncrement_id(),"Custmer_number"=>$order->getCuracaocustomernumber(),'AR_Estimate'=>$order->getEstimatenumber(), "Billing_Name"=>$order->getCustomer_firstname().' '.$order->getCustomer_lastname() ,"State"=>$order->getState(), "Status"=>$order->getStatus(), "Store"=>$store, "Order_date"=>$order->getCreated_at(),"Units"=>$order->getTotal_qty_ordered(),'Subtotal'=>$order->getSubtotal(),'Shipping'=>$order->getShipping_amount(),'Tax'=>$order->getTax_amount(),'Discount_Amount'=>$order->getDiscount_amount(),'Customer_account_balance'=>$order->getCustomer_balance_amount(), 'Grand_Total'=>$order->getGrand_total(),'Payment'=>$payment,"Downpayment"=>$dp,"Curacao_credit_payment"=>$order->getCuracaocustomerdiscount());
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
