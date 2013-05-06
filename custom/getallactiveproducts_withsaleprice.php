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
		->addAttributeToFilter('status', 1)
		->addAttributeToFilter('special_price', array('neq' => ""))
		->setCurPage(4); // set the offset (useful for pagination)
		
		// we iterate through the list of products to get attribute values
		$j = 1;
		foreach ($collection as $product) {
			
//			echo "(".$j.")".$product->getSku()."<br>";
			
			$cat_ids = Mage::getResourceSingleton('catalog/product')->getCategoryIds($product);
			$cName = array();
			for($i = 0;$i<(sizeof($cat_ids)-1);$i++){
				$cName[] = Mage::getModel('catalog/category')->load($cat_ids[$i])->getName();
			}		
			$cat = implode('_',$cName);					
			
			$url = 'http://www.icuracao.com/'.$product->getUrlPath();
			
			$qtyStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();		

		    $image  = Mage::getModel('catalog/product_media_config')
            ->getMediaUrl( $product->getImage() );
			
			if(($product->getPrice()-$product->getSpecialPrice())<1){
			
			$_product = Mage::getModel('catalog/product');
			$productId = $product->getId();
	
			$_product->setStoreId(1)->load($productId);
			//$_product->load($row['product_id']);
			
			$_product->setSpecialPrice('');
			
			$_product->setSpecialFromDate('');
			$_product->setSpecialFromDateIsFormated(true);
			
			$_product->setSpecialToDate('');
			$_product->setSpecialToDateIsFormated(true);
			
			try {
				
				$_product->save();
				echo ' Product has been updated in english ';
				
			}
			catch (Exception $ex) {
				echo $ex->getMessage();
			}
			
			$_product->setStoreId(3)->load($productId);	
			//$_product->load($productId);
			$_product->setSpecialPrice('');	
			$_product->setSpecialFromDate('');
			$_product->setSpecialFromDateIsFormated(true);
			$_product->setSpecialToDate('');
			$_product->setSpecialToDateIsFormated(true);
			
			try {
				
				$_product->save();
				echo ' Product has been updated in spanish ';
				
				
			}
			catch (Exception $ex) {
				echo $ex->getMessage();
			}	
				
			//$data[] = array( "product_id"=>$product->getId(),"name"=>$product->getName(), "sku"=>$product->getSku(),"URL"=>$url,"Image_URL"=>$image,"category_tree"=>$cat,"QTY"=>$qtyStock, "price"=>$product->getPrice(), "Special_price"=>$product->getSpecialPrice(), "Cost_price"=>$product->getCost(),"shipping"=>$product->getShprate(),"Status"=>$product->getStatus());
			
			  $j++;
			}
		}
	echo 'Done scrapping specials';
exit;
	  $filename = "Magento_Active_Products.xls";
	
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
	
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);
	$n = 1;
	$m = 1;
	$sql = "SELECT * FROM `finalproductlist` WHERE `magento_product_id` != '' AND magento_product_id !=0";
	$result = mysql_query($sql);
	while($row=mysql_fetch_array($result)){
		$product = Mage::getModel('catalog/product');
		$product->load($row['magento_product_id']);
		if(sizeof($product->getSmallImage())){
			//echo sizeof($product->getSmallImage());
			if($row['product_img_path']!=''){
				$m++;
			}	
			$n++;
		}
		
//		exit;
	}
	
	echo $n .'No Image In Magento'. $m . ' No Image in Database'; ;