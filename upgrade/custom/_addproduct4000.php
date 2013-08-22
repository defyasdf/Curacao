<?php
	error_reporting(E_ALL | E_STRICT);

	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	ini_set('max_execution_time', 0);	
	
	$mageFilename = '/var/www/m113/app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	umask(0);
	Mage::app('default'); 
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
 
	//$path = getcwd().'/CategoryProducts.csv';
	//$readfile = file ($path);
	// AR connection
	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	// Set headers
	$headerbody = array('UserName' => 'mike', 
						'Password' => 'ecom12'); 
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
	
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_production';
	$link = mysql_connect($server,$user,$pass);
	$link1 = mysql_connect($server,$user,$pass,true);

	mysql_select_db($db,$link) or die("No DB");	
	mysql_select_db('cdc',$link1) or die("No DB");	

	$magentodirect = 'select * from directmagentotable where status = 1';
	$magentodirectresult = mysql_query($magentodirect,$link1);
	while($row = mysql_fetch_array($magentodirectresult)){	
//	$productAvail = false;
	$productAvail = Mage::getModel('catalog/product')->loadByAttribute('sku',$row['product_sku']);
	//if(!$product)	
	if(!$productAvail){
		
		$credit = $proxy->GetSkuPrice(array('Sku'=>$row['product_sku']));
	
		$result = $credit->GetSkuPriceResult;
		
		$price_info = explode('|',$result);
		$cost = '';
		$price = '';
		if($price_info[0]=='OK'){
			if($price_info[2]>0){
				$rebate_price = $price_info[1]-$price_info[3];
				if($rebate_price<$price_info[2]){
					$price = $rebate_price;
				}else{
					$price = $price_info[2];
				}
			}else{
					$price = $price_info[1]-$price_info[3];
			}
			$cost = $price_info[6];
			$price = $price_info[1];
		}
		
		
		
		
		/*echo $essql = 'select * from spenishdata where product_sku = "'.$row['product_sku'].'"';
		$esresult = mysql_query($essql);
		
		$esrow = mysql_fetch_array($esresult);*/
		
		
		//$categoryId = $row['magento_category_id'];
		
		$list = get_html_translation_table(HTML_ENTITIES);
		
		unset($list['"']);
		unset($list['<']);
		unset($list['>']);
		unset($list['&']);
		
		$search = array_keys($list);
		$values = array_values($list);
		
		
		
		$name = $row['prduct_name'];
		$short = $row['short_description'];
		$full = $row['product_description'];
		$feature = str_replace($search, $values, $row['product_features']);
		$spacs = str_replace($search, $values, $row['product_specs']);
		
		
		//$str_out = str_replace($search, $values, $str_in);
		
		//$esname = str_replace($search, $values, $esrow['prduct_name']);
		//$esshort = str_replace($search, $values, $esrow['short_description']);
		//$esfull = str_replace($search, $values, $esrow['product_description']);
		//$esfeature = str_replace($search, $values, $esrow['product_features']);
		//$esspacs = str_replace($search, $values, $esrow['product_specs']);
		
		
		/*$esname = htmlentities($esrow['prduct_name'].ENT_QUOTES);
		$esshort = htmlentities($esrow['product_description'],ENT_QUOTES);
		$esfull = htmlentities($esrow['product_description'],ENT_QUOTES);
		$esfeature = $esrow['product_features'];
		$esspacs = $esrow['product_specs'];*/
		
		//Get Qty 
		
		$credit = $proxy->InventoryLevel(array('cItem_ID'=>$row['product_sku'],'cLocations'=>'16'));
		$result = $credit->InventoryLevelResult;
		$s = explode("\\",$result);
		$tot = 0;
		$storeinv = 0;
		for($i=0;$i<(sizeof($s)-1);$i++){
			$inv = explode("|",$s[$i]);
			$sku = '';
		//	$sku = explode("-",$row['product_sku']);
			$sku = str_replace('-','',substr($row['product_sku'],0,3));
			$query = 'select * from thrashold16 where dept = "'.trim($sku).'"';
			$res = mysql_query($query,$link1) or die(mysql_error());
			$num = mysql_num_rows($res);
			if($num>0){
				$row1 = mysql_fetch_array($res);
				$storeinv += $inv[1];
				
				if($row1['thrashold'] == 'no' || trim($row1['thrashold']) == ''){
					$tot = 0;
				}else{
					$thr = (int)$row1['thrashold'];
					if($inv[1]-$thr>=1){
						$tot += $inv[1]-$thr;
					}
				}
			}
		}
		
		
		$sku = $row['product_sku'];
		//$qty = $row['product_qty'];
		$qty = 10;	
		
		$mage_cat_ids = explode('_',$row['product_category']);
		$final_cat = array();
		
		for($i=0;$i<sizeof($mage_cat_ids);$i++){
			if(trim($mage_cat_ids[$i])!=''){
				$final_cat[] = $mage_cat_ids[$i];
				
			}
		}
			
			$qb = "SELECT * FROM `eav_attribute_option_value` WHERE `value` LIKE '".$row['product_brand']."'";
			$bre = mysql_query($qb,$link);
			//$optionid = '';
			
			 $product = new Mage_Catalog_Model_Product();
			 $product->setTypeID("simple");
			 $product->setVisibility(4);
			 $product->setStatus(1);
			 $product->setWebsiteIds(array(1));
			 $product->setStoreIds(array(1,3));
			 $product->setAttributeSetId(4);
			 $product->setTaxClassId(2);
			 $product->setCost($cost);
			 if(mysql_num_rows($bre)>0){
				$brandrow = mysql_fetch_array($bre);
				$optionid = $brandrow['option_id'];
				$product->setTvBrand($optionid);
			}
			 
			 $product->setSku($sku);
			 $product->setWeight($row['weight']);
			 $product->setName($name);
			 $product->setShortDescription(htmlspecialchars_decode($short,ENT_QUOTES));
			 $product->setDescription(htmlspecialchars_decode($full,ENT_QUOTES));
			 $product->setPrice($price);
			 $product->setVendorid('2139');
			 
			 if($price!=$price_info[1]){
				$product->setSpecialPrice($price);
				$product->setSpecialFromDate(date("Y-m-d", time() - 60 * 60 * 24));
				$product->setSpecialFromDateIsFormated(true);
				
				$product->setSpecialToDate(date("Y-m-d", time() + 60 * 60 * 24));
				$product->setSpecialToDateIsFormated(true);
			}else{
				$product->setSpecialPrice('');
			}
			 $product->setFeature(htmlspecialchars_decode($feature,ENT_QUOTES));
			 $product->setSpec001(htmlspecialchars_decode($spacs,ENT_QUOTES));
			 $product->setStockData(array('is_in_stock' => 1, 'qty' => $tot));
			 $product->setCategoryIds($final_cat);	
	
			$img = explode(',',$row['product_img_path']);
			
			for($i=0;$i<sizeof($img);$i++){
				if(trim($img[$i]) != ''){
						$mgi = explode('.',$img[$i]);
						if(strtolower($mgi[sizeof($mgi)-1]) == 'jpg' || strtolower($mgi[sizeof($mgi)-1]) == 'png' || strtolower($mgi[sizeof($mgi)-1]) == 'gif'){
							//if(file_exists(trim($img[$i]))){
							//echo 'here';
								//if(file_get_contents(trim($img[$i]))){	
									try{
										$from = $img[$i];
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
	
									/*$url = trim($img[$i]);
									$image = 'media/images/'.str_replace('/','_',$sku).'_'.$i.'.jpg';
									file_put_contents($image, file_get_contents($url));*/
									
								//}
							//}
						}
					}
			}
			
	
			try {
				
				$product->save();
				echo '<h4 class="alert_success"> : '.$sku.' Product has been Updated to magento please review the final product in magento and turn it on.</h4>';
				
			}
			catch (Exception $ex) {
				echo $ex->getMessage();
			}
			
		}
		
		$quer = "update directmagentotable set status = 2 where fpl_id = ".$row['fpl_id'];
		mysql_query($quer,$link1);
		
	}
	
	
?>