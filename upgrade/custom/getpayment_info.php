<?php
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set("memory_limit","1024M");
ini_set('apc.cache_by_default','Off');
//DB settings
$server = '192.168.100.121';
$user = 'curacaodata';
$pass = 'curacaodata';
$db = 'curacao_magento';
$link = mysql_connect($server,$user,$pass);
mysql_select_db($db,$link);	

$mageFilename = '/var/www/upgrade/app/Mage.php';
require_once $mageFilename;
Varien_Profiler::enable();
Mage::setIsDeveloperMode(true);
umask(0);
Mage::app('default'); 
//Getting current store ID	
$currentStore = Mage::app()->getStore()->getId();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$sql = "SELECT * FROM `sales_flat_order` where created_at >= '2013-08-29' and hid != ''";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)){
	$order = Mage::getModel('sales/order')->load($row['entity_id']);
	$payment = $order->getPayment()->getData();
	$last4 = '';
	if(isset($payment['additional_information']['authorize_cards'])){
		foreach($payment['additional_information']['authorize_cards'] as $k=>$v){
			 $last4 = $v['cc_last4'];
		}
	}
	
	
	$billing_address = $order->getBillingAddress();
	$billingdata = $billing_address->getData();
	
	
	$data[] = array("Order_id"=>$order->getId(),"Order Number"=>$order->getIncrement_id(),"Billing_Name"=>$order->getCustomer_firstname().' '.$order->getCustomer_lastname(), "Status"=>$order->getStatus(),  "Order_date"=>$order->getCreated_at(),'Subtotal'=>$order->getSubtotal(),'Shipping'=>$order->getShipping_amount(),'Tax'=>$order->getTax_amount(),'Coupon_Code'=>$order->getCoupon_code(),'Discount_Amount'=>$order->getDiscount_amount(), 'Grand_Total'=>$order->getGrand_total(),"Last4"=>"'".$last4."'","Address"=>str_replace("\t","",str_replace("\r\n","",$billingdata['street'])), "City"=>$billingdata['city'],"State"=>$billingdata['region'],"ZIP"=>$billingdata['postcode']);
	
}

$filename = "Magento_Order_with_GWM.xls";
	
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