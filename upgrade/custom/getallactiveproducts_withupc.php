<?php
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	ini_set('apc.cache_by_default', 0); 
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_production';
	
	
	
	$link = mysql_connect($server,$user,$pass);
	$link1 = mysql_connect($server,$user,$pass,true);
	
	mysql_select_db($db,$link) or die("No DB");	
	mysql_select_db('icuracaoproduct',$link1) or die("No DB");	


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
			$sql = "SELECT cc . * FROM catalog_category_entity cc JOIN catalog_category_product cp ON cc.entity_id = cp.category_id WHERE cp.product_id = ".$product->getId()." AND cc.path NOT LIKE '1/2/466%' ORDER BY `cc`.`level` DESC";
			$result = mysql_query($sql,$link);
			$row = mysql_fetch_array($result);
			
			$cat_id = str_replace('1/2/','',$row['path']);
			$cattree = explode('/',$cat_id);
			$cName = array();
			/*print_r($cattree);
			exit;
			
			$cid = array();
			for($i = 0;$i<(sizeof($cat_ids));$i++){
				if(isset($cat_ids[$i])){
					$scat = Mage::getModel('catalog/category')->load($cat_ids[$i]);
					$subcat = $scat->getChildren();
					if(($i+1)<sizeof($cat_ids)){
						if(in_array($cat_ids[$i+1],$subcat)){
							$cid[] = $cat_ids[$i];
							
							unset($cat_ids[$key]);
						}elseif(in_array($scat->parent_id,$cat_ids)){
							$cid[] = $scat->parent_id;
							$key = array_search($scat->parent_id, $cat_ids); // $key = 2;
							unset($cat_ids[$key]);
						}
					}elseif(in_array($scat->parent_id,$cat_ids)){
						$cid[] = $scat->parent_id;
						$key = array_search($scat->parent_id, $cat_ids); // $key = 2;
						unset($cat_ids[$key]);
					}	
				}
				//$cName[] = Mage::getModel('catalog/category')->load($cat_ids[$i])->getName();
				
			}*/		
			//$cattree = array_merge($cid,$cat_ids);	
			/*print_r($cattree);
			exit;*/
			for($j=0;$j<sizeof($cattree);$j++){
				if(!in_array($cattree[$j],$final_list)){
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
			$result = mysql_query($sql,$link1);
			$row = mysql_fetch_array($result);
			
			if($product->getVendorid()!=''){
				$vsql = "SELECT vendorName FROM `vendormanagement` WHERE `vendorID` = '".$product->getVendorid()."'";
				$vresult = mysql_query($vsql,$link1);
				$vrow = mysql_fetch_array($vresult);
			}
			
			$data[] = array( "product_id"=>$product->getId(),"name"=>$product->getName(), "sku"=>$product->getSku(),"UPC"=>$row['product_upc'],"URL"=>$url,"Image_URL"=>$image,"category_tree"=>$cat,"QTY"=>$qtyStock, "price"=>$product->getPrice(), "Special_price"=>$product->getSpecialPrice(),"Special_From_date"=>$product->getspecial_from_date(),"Special_To_date"=>$product->getspecial_to_date(), "Cost_price"=>$product->getCost(),"shipping"=>$product->getShprate(),"Status"=>$product->getStatus(),"Vendor"=>$vrow['vendorName']);
			
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
	
	
