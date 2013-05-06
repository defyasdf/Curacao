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
	
	
//	$collection = Mage::getModel('sales/order')->getCollection();
//	foreach ($collection as $order) {

	$sql = "select * from order_to_invoiced";
	$result = mysql_query($sql,$link) or die(mysql_error());
//	while($row = mysql_fetch_array($result)){
		//if(trim($row['paymentmethod']) == 'Curacao Credit'){ 
		$_order = new Mage_Sales_Model_Order();
		$_order->loadByIncrementId('100001197');
		if($_order->canInvoice()) {
		/**
		 * Create invoice
		 * The invoice will be in 'Pending' state
		 */
		$invoiceId = Mage::getModel('sales/order_invoice_api')
							->create($_order->getIncrementId(), array());
	 
		$invoice = Mage::getModel('sales/order_invoice')
							->loadByIncrementId($invoiceId);
	 		
		$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
		
		$invoice->register();	
		
		$transactionSave = Mage::getModel('core/resource_transaction')
							->addObject($invoice)
							->addObject($invoice->getOrder());
		$transactionSave->save();
			
		/**
		 * Pay invoice
		 * i.e. the invoice state is now changed to 'Paid'
		 */
		
			//$invoice->capture()->save();

			$invoice->save();
		}
	//}

echo ' Invoice created  <br>';

//}