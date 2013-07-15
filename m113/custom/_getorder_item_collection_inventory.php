<?php

	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_magento';
	
	
	
	$link = mysql_connect($server,$user,$pass);
	$link1 = mysql_connect($server,$user,$pass,true);
	
	mysql_select_db($db,$link) or die("No DB");	
	mysql_select_db('icuracaoproduct',$link1) or die("No DB");	


	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('admin'); 
	
	$collection = Mage::getModel('sales/order')->getCollection();
	
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
		/*$cat = $product->getCategoryIds();
		//$cat_ids = implode('_',$cat);
		
		$cName = array();
		for($i = 0;$i<(sizeof($cat)-1);$i++){
			$cName[] = Mage::getModel('catalog/category')->load($cat[$i])->getName();
		}*/
	//	echo $product->getId()." : ".$item->getSku()." <br />" ;
		$cat_ids = '';	
		if($product->getId()){
			$sql = "SELECT cc . * FROM catalog_category_entity cc JOIN catalog_category_product cp ON cc.entity_id = cp.category_id WHERE cp.product_id = ".$product->getId()." AND cc.path NOT LIKE '1/2/466%' ORDER BY `cc`.`level` DESC";
				$result = mysql_query($sql,$link);
				$row = mysql_fetch_array($result);
				
				$cat_id = str_replace('1/2/','',$row['path']);
				$cattree = explode('/',$cat_id);
				$cName = array();
				
				for($j=0;$j<sizeof($cattree);$j++){
					if(!in_array($cattree[$j],$final_list)){
						$cName[] = Mage::getModel('catalog/category')->load($cattree[$j])->getName();
					}
				}
			$cat_ids = implode('_',$cName);	
		}
		
		
		$data[] = array("Order_id"=>$order->getId(),"Order Number"=>$order->getIncrement_id(),"Custmer_number"=>$order->getCuracaocustomernumber(),'AR_Estimate'=>$order->getEstimatenumber(), "State"=>$order->getState(), "Status"=>$order->getStatus(), "Store"=>$store, "Order_date"=>$order->getCreatedAtStoreDate(),"Units_per_product"=>$item->getQty_ordered(),'sku'=>$item->getSku(),'Name'=>$item->getName(),'UNIT_PRICE'=>$item->getPrice(),"Cost_Price"=>$product->getCost(),"Margin"=>$margin,"Percent_Margin"=>$percent.' %', "Category_Ids"=>$cat_ids);
		
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
