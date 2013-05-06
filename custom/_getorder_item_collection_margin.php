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
	
	$string = '';
	function getcattree($catId){
		global $string;
		 $m = Mage::getModel('catalog/category')
			->load($catId)
			->getParentCategory();
		
		//$array = array_push($array,$m->getId());	
		if($m->getId()!=2){
			$string .= $m->getId()."_";		
		}
		 if($m->getLevel()>2){
			getcattree($m->getId());
		  }
	
		return $string;
	}
	if(isset($_REQUEST['edate'])){
	$dT = explode('/',$_REQUEST['edate']);
	$dF = explode('/',$_REQUEST['sdate']);
	if(trim($_REQUEST['edate'])!=''){
		$to = $dT[2].'-'.$dT[0].'-'.$dT[1];

		 
	}
	if(trim($_REQUEST['sdate'])!=''){
			$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
	}
	}
	$collection = Mage::getModel('sales/order')->getCollection();
	if(isset($_REQUEST['edate'])){
	if(trim($_REQUEST['edate'])!='' && trim($_REQUEST['sdate'])!=''){
	$collection->addAttributeToFilter('created_at', array(
            'from'  => $from,
            'to'    => $to,                    
        )); 
	}
	}
	foreach ($collection as $order) {
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
		$margin = $item->getPrice() - $product->getCost();
		if($item->getPrice()>0){
			$percent = $margin/$item->getPrice();
			$percent = $percent*100;
		}else{
			$percent = '';
		}
		$cat = $product->getCategoryIds();
		$cat_ids = implode('_',$cat);
		
		
		
		$data[] = array("Order_id"=>$order->getId(),"Order Number"=>$order->getIncrement_id(),"Custmer_number"=>$order->getCuracaocustomernumber(),'AR_Estimate'=>$order->getEstimatenumber(), "State"=>$order->getState(), "Status"=>$order->getStatus(), "Store"=>$store, "Order_date"=>$order->getCreated_at(),"Units_per_product"=>$item->getQty_ordered(),'sku'=>$item->getSku(),'Name'=>$item->getName(),'UNIT_PRICE'=>$item->getPrice(),"Cost_Price"=>$product->getCost(),"Margin"=>$margin,"Percent_Margin"=>$percent.' %', "Category_Ids"=>$cat_ids);
		
	}
	}

	
	  $filename = "Magento_Order_with_Item_margin.xls";
	
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
