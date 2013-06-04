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
 	
	$cat = Mage::getModel('catalog/category')->load(466);
	$subcatcollection = $cat->getChildren();
	
	$subcat = explode(",",$subcatcollection);
	
	$cat_collecton = $subcat;
	$final_list = array();
	for($i=0;$i<sizeof($cat_collecton);$i++){
		$scat = $cat = Mage::getModel('catalog/category')->load($cat_collecton[$i]);
		$scatcollection = $scat->getChildren();
		if(trim($scatcollection)!=''){
			$sucat = explode(",",$scatcollection);
			$final_list = array_merge($cat_collecton,$sucat);
		}
	}
	if(sizeof($final_list)==0){
		$final_list = $cat_collecton;
	}
	
	
	$collection = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*') // select all attributes
		->addAttributeToFilter('status', 1)
		->setCurPage(4); // set the offset (useful for pagination)
		
		// we iterate through the list of products to get attribute values
		$j = 1;
		foreach ($collection as $product) {
			
//			echo "(".$j.")".$product->getSku()."<br>";
			
			$cat_ids = Mage::getResourceSingleton('catalog/product')->getCategoryIds($product);
			$cName = array();
			$cid = array();
			for($i = 0;$i<(sizeof($cat_ids));$i++){
				if(isset($cat_ids[$i])){
					$scat = $cat = Mage::getModel('catalog/category')->load($cat_ids[$i]);
					
					if(in_array($scat->parent_id,$cat_ids)){
						$cid[] = $scat->parent_id;
						$key = array_search($scat->parent_id, $cat_ids); // $key = 2;
						unset($cat_ids[$key]);
					}	
				}
				//$cName[] = Mage::getModel('catalog/category')->load($cat_ids[$i])->getName();
				
			}		
			$cattree = array_merge($cid,$cat_ids);	
			for($j=0;$j<sizeof($cattree);$j++){
				if(!in_array($cattree[$i],$final_list)){
					$cName[] = Mage::getModel('catalog/category')->load($cattree[$j])->getName();
				}
			}
			
			$cat = implode('_',$cName);					
			
			$url = 'http://www.icuracao.com/'.$product->getUrlPath();
			
			$qtyStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();		

		    $image  = Mage::getModel('catalog/product_media_config')
            ->getMediaUrl( $product->getImage() );
			
			$s = explode('-',$product->getSku());
			if(sizeof($s)==3){
				if($s[0]=='cur'){
					$sku = $s[2];
				}else{
					$sku = $product->getSku();
				}
			}else{
				if($s[0]=='cur'){
						$sku = $s[sizeof($s)-1];
					}else{
						$sku = $product->getSku();
					}
				//$sku = $product->getSku();
			}
			
			$sql = "SELECT product_upc FROM `masterproducttable` WHERE `product_sku` = '".$sku."' limit 0,1";
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			
			$data[] = array( "product_id"=>$product->getId(),"name"=>$product->getName(), "sku"=>$product->getSku(),"UPC"=>$row['product_upc'],"URL"=>$url,"Image_URL"=>$image,"category_tree"=>$cat,"QTY"=>$qtyStock, "price"=>$product->getPrice(), "Special_price"=>$product->getSpecialPrice(),"Special_From_date"=>$product->getspecial_from_date(),"Special_To_date"=>$product->getspecial_to_date(), "Cost_price"=>$product->getCost(),"shipping"=>$product->getShprate(),"Status"=>$product->getStatus());
			
			  $j++;

		}
	

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