<?php
//exit;
	ini_set('max_execution_time', 0);
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	require_once('../dbw.php');
	require_once('../urls.php');
	require_once('Mfunctions.php');
	$result = TRUE;
	if($result == TRUE)
	{
	require_once(MAGE_FILE_URL);
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
        $currentStore = Mage::app()->setCurrentStore(1);
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
			$contents = get_catalog_data();
			while($content = mysql_fetch_object($contents))
			{
			echo $content->sku."<br>";
        	$magentoproductIds = Mage::getModel('catalog/product')->loadByAttribute('sku',trim($content->sku));
			if($magentoproductIds)
			{
			echo "Product With Same SKU already exist";	
				echo $content->sku."Already There<br>";	
			}
			else
			{
			

		$list = get_html_translation_table(HTML_ENTITIES);
		
		unset($list['"']);
		unset($list['<']);
		unset($list['>']);
		unset($list['&']);
		
		$search = array_keys($list);
		$values = array_values($list);




			// add new product
			$product = new Mage_Catalog_Model_Product();
			 $product->setTypeID("simple");
			 $product->setVisibility(1);
			 $product->setWebsiteIds(array(1));
			 $product->setStoreIds(array(1));
			 $product->setAttributeSetId(4);
			 $product->setTaxClassId(2);
			 $sku = $content->sku;
			 $product->setSku($content->sku);
			 $product->setupc($content->upc);
			 $product->setMeta_title($content->product_name);
			 $categories = explode("_",$content->category_tree);
			 $product->setCategoryIds($categories);

$sku = $content->sku;
			$newurl = htmlspecialchars_decode($content->product_name,ENT_QUOTES);
			$creaturl = cleaningdata($newurl);
			$strlower = strtolower($creaturl);
			$url = str_replace(" ","-",$strlower);

				$product->seturl_key($url);
			 $product->setorig_name($content->product_name);
			 $product->setName($content->product_name);

			 
			 $product_description = str_replace($search, $values,$content->Product_Description);

  			 $product_description_withbr = nl2br($product_description);

			 $product->setDescription(htmlspecialchars_decode($product_description_withbr,ENT_QUOTES));

				$product_specifications = str_replace($search, $values,$content->Specifications);

  			 $product_specifications_withbr = nl2br($product_specifications);

			 $product->setspec001(htmlspecialchars_decode($product_specifications_withbr,ENT_QUOTES));

				$product->setweight($content->Weight);
				// $product->setweight($content->Dimensions_Height);
				// $product->setweight($content->Dimensions_Width);
				// $product->setweight($content->Dimensions_Depth);
			 $product->setsproductvideo($content->Video_Link);
			 $product->setspecial_price($content->Special_price);
			 $fromdate = date('m/d/Y',strtotime($content->From_date));
			 $product->setspecial_from_date($fromdate);
			 $todate = date('m/d/Y',strtotime($content->To_date));
			 $product->setspecial_to_date($todate);
			 $product->settv_brand($content->Brand);
			 $product->setvendorid($content->source);
			 $product->setPrice($content->Retail);
			 $product->setcost($content->Cost);
			 $product->setStockData(array('is_in_stock' => 1, 'qty' => $content->Inventory));
			 $product->setstatus("2");

$img = explode(',',$content->Image_path);
			
			for($i=0;$i<sizeof($img);$i++){
			if(trim($img[$i]) != ''){
					
					//if(file_exists(trim($img[$i]))){
					//echo 'here';
						if(file_get_contents(trim($img[$i]))){	
							$from = $img[$i];
							$image = IMAGES_MAGENTO_LOCATION_URL.str_replace('/','_',$sku).'_'.$i.'.jpg';;
							if(!@copy($from,$image))
							{
								$errors= error_get_last();
								echo $errors['message'];
							
							} else {
														
								$product->addImageToMediaGallery ($image, array('image','small_image','thumbnail'), false, false);
							}
					}
				}
			}


			 // print_r($product);
			 // exit;
			// assign product to the default website
 			try {
				
				$product->save();
				echo $content->sku." Updated<br>";
			}
			catch (Exception $ex) {
				echo $ex->getMessage();
				echo $content->sku."Not Updated<br>";
			}
	//exit;
			}
			}
			}	
?>