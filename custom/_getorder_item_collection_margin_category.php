<?php

	require_once('./_includes/mage_head.php');
	
	$collection = Mage::getModel('sales/order')->getCollection();
	/*$collection->addAttributeToFilter('created_at', array(
            'from'  => '2013-01-01',
            'to'    => '2013-01-15',                    
        ));
*/	foreach ($collection as $order) {
		//echo $order->getPayment().'<br>';
		
		$getorder = new Mage_Sales_Model_Order();
	
		$getorder->loadByIncrementId($order->getIncrement_id());
		
		$payment = $getorder->getPayment()->getMethodInstance()->getTitle();
		
		if($order->getStore_id()==1){
			$store = 'English';
		}else{
			$store = 'Spanish';
		}
		
		$items = $order->getAllItems();
		foreach ($items as $itemId => $item){
		
		$product = Mage::getModel('catalog/product');
		$productId = $product->getIdBySku($item->getSku());
		$product->load($productId);

		
		$cat = $product->getCategoryIds();
		for($i=0;$i<sizeof($cat);$i++){
		$cId = $cat[$i];
		
		$_category = Mage::getModel('catalog/category')->load($cId);
			
		$cname = $_category->getName();
		
		
		
		$data[] = array("Order_id"=>$order->getId(),"Category"=>$cname);
		
		}
		//print_r($data);
		//exit;
	}
	}

	
	  $filename = "Magento_Order_with_Item_category_margin.xls";
	
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
