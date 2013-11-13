<?php

	require_once('./_includes/ini_settings.php');
	require_once('./_includes/mage_head.php');	
	
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
	
		$getorder = new Mage_Sales_Model_Order();
		$getorder->loadByIncrementId($order->getIncrement_id());
	
		$payment = $getorder->getPayment()->getMethodInstance()->getTitle();
		
		if($payment == "No Payment Information Required"){
			if($order->getCustomer_balance_amount()>0){
				$payment = "Pay Using Store Credit";
			}
		}
	
		if($order->getStore_id()==1){
			$store = 'English';
		}else{
			$store = 'Spanish';
		}
		$grandTotal = $order->getGrandTotal();
		$dp = $grandTotal - $order->getCuracaocustomerdiscount();
	
		$data[] = array("Order_id"=>$order->getId(),"Order Number"=>$order->getIncrement_id(),"Custmer_number"=>$order->getCuracaocustomernumber(),'AR_Estimate'=>$order->getEstimatenumber(), "Billing_Name"=>$order->getCustomer_firstname().' '.$order->getCustomer_lastname() ,"State"=>$order->getState(), "Status"=>$order->getStatus(), "Store"=>$store, "Order_date"=>$order->getCreatedAtStoreDate(),"Units"=>$order->getTotal_qty_ordered(),'Subtotal'=>$order->getSubtotal(),'Shipping'=>$order->getShipping_amount(),'Tax'=>$order->getTax_amount(),'Discount_Amount'=>$order->getDiscount_amount(),'Customer_account_balance'=>$order->getCustomer_balance_amount(), 'Grand_Total'=>$order->getGrand_total(),'Payment'=>$payment,"Downpayment"=>$dp,"Curacao_credit_payment"=>$order->getCuracaocustomerdiscount());
	}
	
	if(!isset($data))
		$data[] = array("Result"=>"Nothing Found");
	
	
	$filename = "Magento_Order.xls";
	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel");
	
	$flag = false;
	foreach($data as $row) {
	
		if(!$flag) {
			echo implode("\t", array_keys($row)) . "\r\n";
			$flag = true;
		}
		echo implode("\t", array_values($row)) . "\r\n";
	}
	
	
	exit;
