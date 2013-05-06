<?php

	error_reporting(E_ALL | E_STRICT);

	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	ini_set('max_execution_time', 0);	
	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
	$order_id = '100000004';
	
	$order = Mage::getModel('sales/order')->load($order_id);
	$items = $order->getAllItems();
	$itemcount=count($items);
	$name=array();
	$unitPrice=array();
	$sku=array();
	$ids=array();
	$qty=array();
	foreach ($items as $itemId => $item)
	{
		$name[] = $item->getName();
		$unitPrice[]=$item->getPrice();
		$sku[]=$item->getSku();
		$ids[]=$item->getProductId();
		$qty[]=$item->getQtyToInvoice();
	}


print_r($name);