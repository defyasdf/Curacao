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
	
	
	$collection = Mage::getModel('sales/order_shipment')->getCollection();
	$collection->addAttributeToFilter('created_at', array(
            'from'  => '2013-01-01',
            'to'    => '2013-04-16',                    
        ));
		
	foreach ($collection as $order) {
		//echo $order->getCuracaocustomernumber().'<br>';
		if($order->getStore_id()==1){
			$store = 'English';
		}else{
			$store = 'Spanish';
		}
		$items = $order->getAllItems();
		$skus = '';
		foreach ($items as $itemId => $item){
			//$skus .= $item->getSku().',';
			$data[] = array( "Shipment Number"=>$order->getIncrement_id(),"Order_Id"=>$order->getOrder_id(),"SKU"=>$item->getSku(),"Status"=>$order->getShipment_status(), "Store"=>$store, "Shipment_date"=>$order->getCreated_at(),"Units"=>$item->getQty(),'Packages'=>$order->getPackages(), 'shipping_label'=>$order->getShipping_label());
		
		}
		
		
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
