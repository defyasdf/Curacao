<?php
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
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
 	
	
	
	$collection = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*') // select all attributes
		->addAttributeToFilter('vendorid', '2139')
		->setCurPage(4); // set the offset (useful for pagination)
		
		// we iterate through the list of products to get attribute values
		$j = 1;
		foreach ($collection as $product) {
			
//			echo "(".$j.")".$product->getSku()."<br>";
			
			$cat_ids = Mage::getResourceSingleton('catalog/product')->getCategoryIds($product);
					
			$cat = implode('_',$cat_ids);					
			
			$qtyStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();		

						$data[] = array( "product_id"=>$product->getId(),"name"=>$product->getName(), "sku"=>$product->getSku(),"category_tree"=>$cat,"QTY"=>$qtyStock, "price"=>$product->getPrice(), "Special_price"=>$product->getSpecialPrice(), "Cost_price"=>$product->getCost(),"shipping"=>$product->getShprate(),"Status"=>$product->getStatus());


			/*exit;
			
			$q = 'insert into product_to_category (product_id,sku,categories) values("'.$product->getId().'",'.$product->getSku().',"'.implode(',',$product->getCategoryIds).'")';
			mysql_query($q) or die(mysql_error());
			
		  echo '('.$j.')'. $product->getSku().' - '.sizeof($product->getSmallImage()).'<br>'; //get name*/
			  $j++;
		  
		 // exit;
		}
	
//exit;
	  $filename = "Magento_Curacao_Products.xls";
	
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
	
	
	