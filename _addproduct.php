<?php
	error_reporting(E_ALL | E_STRICT);

	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	ini_set('max_execution_time', 0);	
	
	$mageFilename = 'app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
 
	//$path = getcwd().'/CategoryProducts.csv';
	//$readfile = file ($path);
	
	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct');
	
	$sql1 = "SELECT * FROM `magentoque` where mstatus = 0";
	$resu1 = mysql_query($sql1);
	
	while($row1 = mysql_fetch_array($resu1)){	
	
	$sql = 'select * from finalproductlist where fpl_id = '.$row1['finalproId'];
	$result = mysql_query($sql);
	
	$row = mysql_fetch_array($result);
	
	$essql = 'select * from spenishdata where sppr_id = '.$row['spenish_id'];
	$esresult = mysql_query($essql);
	
	$esrow = mysql_fetch_array($esresult);
	
	
	//$categoryId = $row['magento_category_id'];
	
	$list = get_html_translation_table(HTML_ENTITIES);
	
	unset($list['"']);
	unset($list['<']);
	unset($list['>']);
	unset($list['&']);
	
	$search = array_keys($list);
	$values = array_values($list);
	
	
	
	$name = $row['prduct_name'];
	$short = $row['product_description'];
	$full = $row['product_description'];
	$feature = str_replace($search, $values, $row['product_features']);
	$spacs = str_replace($search, $values, $row['product_specs']);
	
	
	//$str_out = str_replace($search, $values, $str_in);
	
	$esname = str_replace($search, $values, $esrow['prduct_name']);
	$esshort = str_replace($search, $values, $esrow['product_description']);
	$esfull = str_replace($search, $values, $esrow['product_description']);
	$esfeature = str_replace($search, $values, $esrow['product_features']);
	$esspacs = str_replace($search, $values, $esrow['product_specs']);
	
	
	/*$esname = htmlentities($esrow['prduct_name'].ENT_QUOTES);
	$esshort = htmlentities($esrow['product_description'],ENT_QUOTES);
	$esfull = htmlentities($esrow['product_description'],ENT_QUOTES);
	$esfeature = $esrow['product_features'];
	$esspacs = $esrow['product_specs'];*/
	
	if((substr($row['product_retail'],0,1)) == '$'){
		$price = (substr($row['product_retail'],1));
	}else{
		$price = $row['product_retail'];
	}
	if((substr($row['product_cost'],0,1)) == '$'){
		$cost = (substr($row['product_cost'],1));
	}else{
		$cost = $row['product_cost'];
	}
	if((substr($row['product_msrp'],0,1)) == '$'){
		$msrp = (substr($row['product_msrp'],1));
	}else{
		$msrp = $row['product_msrp'];
	}
	$sku = $row['product_sku'];
	//$qty = $row['product_qty'];
	$qty = 10;	
	
	$mage_cat_ids = explode('-',$row['magento_category_id']);
	$final_cat = array();
	
	for($i=0;$i<sizeof($mage_cat_ids);$i++){
		if(trim($mage_cat_ids[$i])!=''){
			$final_cat[] = $mage_cat_ids[$i];
			
		}
	}
		
		
		
		 $product = new Mage_Catalog_Model_Product();
		 $product->setTypeID("simple");
		 $product->setVisibility(4);
		 $product->setStatus(1);
		 $product->setWebsiteIds(array(1,2));
		 $product->setStoreIds(array(1,3));
		 $product->setAttributeSetId(4);
		 $product->setTaxClassId(2);
		 $product->setCost($cost);
		 $product->setMsrp($msrp);
		 $product->setSku($sku);
		 $product->setName($name);
		 $product->setShortDescription(htmlspecialchars_decode($short,ENT_QUOTES));
		 $product->setDescription(htmlspecialchars_decode($full,ENT_QUOTES));
		 $product->setPrice($price);
		 $product->setFeature(htmlspecialchars_decode($feature,ENT_QUOTES));
		 $product->setSpec001(htmlspecialchars_decode($spacs,ENT_QUOTES));
		 $product->setStockData(array('is_in_stock' => 1, 'qty' => $row['product_qty']));
		 $product->setCategoryIds($final_cat);	

		$img = explode(',',$row['product_img_path']);
	
		for($i=0;$i<sizeof($img);$i++){
			if(trim($img[$i]) != ''){
				if(file_exists(trim($img[$i]))){
					if(file_get_contents(trim($img[$i]))){	
						$url = trim($img[$i]);
						$image = 'media/images/'.str_replace('/','_',$sku).'_'.$i.'.jpg';
						file_put_contents($image, file_get_contents($url));
						$product->addImageToMediaGallery ($image, array('image','small_image','thumbnail'), false, false); 
					}
				}
			}
		}
		//$fields = split(",",$readfile[$i]);
/*		
		
		$product->load($row['magento_pro_id']);		//field 0 == product_id
		$cagegory_id = $row['main_parent_id'];		//field 1 == category_id
*/		
		// then set product's category

		try {
			
		    $product->save();
			
		}
		catch (Exception $ex) {
		    echo $ex->getMessage();
		}
		
		$product = Mage::getModel('catalog/product');
		$productId = $product->getIdBySku($sku);
		if ($productId) {
			$product->setStoreId(3)->load($productId);
			$product->setName($esname);
			$product->setShortDescription($esshort);
			$product->setDescription($esfull);
			$product->setFeature(htmlspecialchars_decode($esfeature,ENT_QUOTES));
			$product->setSpec001(htmlspecialchars_decode($esspacs,ENT_QUOTES));
			$product->save();
		}
		try {
			
		 //   $product->save();
			
			$sql = "UPDATE `finalproductlist` SET `magento_product_id` = '".$productId."' WHERE `fpl_id` = ".$row1['finalproId'];
				
			if(mysql_query($sql)){
			$sql = "UPDATE `magentoque` SET `mstatus` = '1' WHERE `qId` = ".$row1['qId'];
			mysql_query($sql);
			
				echo '<h4 class="alert_success"> : Product has been added to magento please review the final product in magento and turn it on.</h4>';
			}else{
				echo '<h4 class="alert_warning">Product has been added to magento but record is not updated in the system.</h4>';
			}
		}
		catch (Exception $ex) {
		    echo $ex->getMessage();
		}

		
		
	}
	
	
?>