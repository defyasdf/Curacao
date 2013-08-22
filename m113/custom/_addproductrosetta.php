<?php
	error_reporting(E_ALL | E_STRICT);

	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	ini_set('max_execution_time', 0);	
	
	$mageFilename = '/var/www/dev/app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	umask(0);
	Mage::app('default'); 
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
 
	//$path = getcwd().'/CategoryProducts.csv';
	//$readfile = file ($path);
		
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_production';
	$link = mysql_connect($server,$user,$pass);
	$link1 = mysql_connect($server,$user,$pass,true);

	mysql_select_db($db,$link) or die("No DB");	
	mysql_select_db('cdc',$link1) or die("No DB");	

	$magentodirect = 'select * from directmagento_rosetta where sku = "27746"';
	$magentodirectresult = mysql_query($magentodirect,$link1);
	while($row = mysql_fetch_array($magentodirectresult)){	
//	$productAvail = false;
	$productAvail = Mage::getModel('catalog/product')->loadByAttribute('sku',$row['sku']);
	//if(!$product)	
	if(!$productAvail){
		$cost = $row['cost'];
		$price = $row['retail'];
		
		$list = get_html_translation_table(HTML_ENTITIES);
		
		unset($list['"']);
		unset($list['<']);
		unset($list['>']);
		unset($list['&']);
		
		$search = array_keys($list);
		$values = array_values($list);
		
		$name = $row['title'];
		$short = str_replace(" — ","",$row['product_description']);
		$full = str_replace(" — ","",$row['product_description']);
//		$feature = str_replace($search, $values, $row['product_features']);
//		$spacs = str_replace($search, $values, $row['product_specs']);
		 $sku = $row['sku'];
		echo htmlspecialchars($row['product_description'],ENT_QUOTES).'<br>';
		echo $short;
		exit;
		
		
		//$qty = $row['product_qty'];
	//	$qty = 3;	
		
		$mage_cat_ids = explode('_',$row['category']);
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
			 $product->setWebsiteIds(array(1));
			 $product->setStoreIds(array(1,3));
			 $product->setAttributeSetId(4);
			 $product->setTaxClassId(2);
			 $product->setCost($cost);
			 $product->setSku($sku);
//			 $product->setWeight($row['weight']);
			 $product->setName($name);
			
			 $product->setShortDescription(htmlspecialchars_decode($short,ENT_QUOTES));
			 $product->setDescription(htmlspecialchars_decode($full,ENT_QUOTES));
			 $product->setPrice($price);
			 $product->setVendorid($row['product_source']);
//			 $product->setFeature(htmlspecialchars_decode($feature,ENT_QUOTES));
//			 $product->setSpec001(htmlspecialchars_decode($spacs,ENT_QUOTES));
			 $product->setStockData(array('is_in_stock' => 1, 'qty' => $row['inventory']));
			 $product->setCategoryIds($final_cat);	
	
			/*$img = explode(',',$row['product_img_path']);
			
			for($i=0;$i<sizeof($img);$i++){
			if(trim($img[$i]) != ''){
					$mgi = explode('.',$img[$i]);
					if(strtolower($mgi[sizeof($mgi)-1]) == 'jpg' || strtolower($mgi[sizeof($mgi)-1]) == 'png' || strtolower($mgi[sizeof($mgi)-1]) == 'gif'){
						
								try{
									$from = str_replace('H3http://','http://',$img[$i]);
									$image = '../media/images/'.str_replace('/','_',$sku).'_'.$i.'.jpg';;
									if(!@copy($from,$image))
									{
										$errors= error_get_last();
										echo $errors['message'];
									
									} else {
																
										$product->addImageToMediaGallery ($image, array('image','small_image','thumbnail'), false, false); 
									}
								}catch (Exception $ex) {
									echo $ex->getMessage();
								}

								
					}
				}
			}*/
			
	
			try {
				
				$product->save();
				echo '<h4 class="alert_success"> : '.$sku.' Product has been Updated to magento please review the final product in magento and turn it on.</h4>';
				
			}
			catch (Exception $ex) {
				echo $ex->getMessage();
			}
			
		}
		
		/*$quer = "update directmagentotable_jewellery set status = 2 where fpl_id = ".$row['fpl_id'];
		mysql_query($quer,$link1);*/
		
	}
	
	
?>