<?php
	ini_set('max_execution_time', 300);
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	require_once('../dbw.php');
	require_once('../urls.php');
	require_once('Mfunctions.php');
	$result = TRUE;
	if($result == TRUE)
	{
	require_once(MAGE_FILE_URL_DEMO);
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
        $currentStore = Mage::app()->setCurrentStore(1);
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
			$contents = get_rosetta_data();
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
			 $product->setStatus(0);
			 $product->setWebsiteIds(array(1));
			 $product->setStoreIds(array(1));
			 $product->setAttributeSetId(4);
			 $product->setTaxClassId(2);
			 $sku = $content->sku;
			 $product->setSku($content->sku);
			 $product->setupc($content->upc);
			 $product->setMeta_title($content->title);
			 $categories = explode("_",$content->category);
			 $product->setCategoryIds($categories);


			$newurl = htmlspecialchars_decode($content->title,ENT_QUOTES);
			$creaturl = cleaningdata($newurl);
			$strlower = strtolower($creaturl);
			$url = str_replace(" ","-",$strlower);

				$product->seturl_key($url);
			 $product->setorig_name($content->title);
			 $product->setName($content->title);

			 
			 $product_description = str_replace($search, $values,$content->product_description);

  			 $product_description_withbr = nl2br($product_description);

			 $product->setDescription(htmlspecialchars_decode($product_description_withbr,ENT_QUOTES));


			 $product->settv_brand($content->brand);
			 $product->setvendorid($content->product_source);
			 $product->setPrice($content->retail);
			 $product->setcost($content->cost);
			 $product->setStockData(array('is_in_stock' => 1, 'qty' => $content->inventory));
			 $product->setstatus("2");



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
exit;	
			}
			}
			}	
?>