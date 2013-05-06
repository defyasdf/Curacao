<?php

// INI setting
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';

	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);	
		
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('admin'); 
	
	

	$sql = "SELECT * FROM `order_to_invoiced` WHERE `paymentmethod` LIKE 'Credit Card Payment'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)){
	
		$order = Mage::getModel("sales/order")->loadByIncrementId($row['order_id']);
		try {
		if(!$order->canInvoice())
		{
			echo 'here';
			Mage::throwException(Mage::helper('core')->__('Cannot create an invoice.'));
		}
		$invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
		if (!$invoice->getTotalQty()) {
			Mage::throwException(Mage::helper('core')->__('Cannot create an invoice without products.'));
		}
		$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
		$invoice->register();
		$transactionSave = Mage::getModel('core/resource_transaction')
		->addObject($invoice)
		->addObject($invoice->getOrder());
		$transactionSave->save();
		}
		catch (Mage_Core_Exception $e) {
		}
			
		
		echo ' Invoice created  <br>';

}