<?php
	ini_set('max_execution_time', 3000);
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	require_once('../dbw.php');
	require_once('../urls.php');
	require_once('Mfunctions.php');
	// $fpl_id = $_GET['fpl_id'];
	$result = true;
	
	// removing special Character data strt
			$list = get_html_translation_table(HTML_ENTITIES);
			unset($list['"']);
			unset($list['<']);
			unset($list['>']);
			unset($list['&']);		
			$search = array_keys($list);
			$values = array_values($list);
			// removing special Character data end
	
	if($result == TRUE)
	{
	require_once(MAGE_FILE_URL);
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
        $currentStore = Mage::app()->setCurrentStore(1);
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
			$contents = get_jewellery_from_table_data();

			while($content = mysql_fetch_object($contents))
			{
			echo "product sku - ".$content->product_sku."<br>";

        	$product_id = Mage::getModel('catalog/product')->getIdBySku($content->product_sku);
			if($product_id)
			{
			$product = Mage::getModel('catalog/product');
			$product ->load($product_id);
			// check description
		if($product->getdescription() == "")
			{

				if($content->product_description != "")
				{
			 $product_description = str_replace($search, $values, $content->product_description);
			 $product->setDescription(htmlspecialchars_decode($product_description,ENT_QUOTES));
				}
				else
				{
			 $product_shortdescription = str_replace($search, $values, $content->short_description);
			 $product->setDescription(htmlspecialchars_decode($product_shortdescription,ENT_QUOTES));
				}
			}
			 $product_shortdescription1 = str_replace($search, $values, $content->short_description);
			 $product->setshort_description(htmlspecialchars_decode($product_shortdescription1,ENT_QUOTES));
			// check description
			// set URL and product name
			$newurl = htmlspecialchars_decode($content->prduct_name,ENT_QUOTES);
			$creaturl = cleaningdata($newurl);
			$strlower = strtolower($creaturl);
			$url = str_replace(" ","-",$strlower);

			 $product->seturl_key($url);
			 $product->setorig_name($content->prduct_name);			
			// set URL and product name
			 $product->setweight("1.00");
			 $product->setMeta_title($content->prduct_name);
			 $product->settv_brand(810);
				// status
				if($content->product_inventory_level == 0 OR $content->product_inventory_level == "")
				{
			 	 $product->setVisibility(4);
				 $product->setstatus("2");
				}

$categorys = array(
    "0" => "10",
    "1" => "36",
);
$product->setCategoryIds($categorys);


				// status
			 // try to save start
			 try {
				$product->save();
				echo $content->product_sku." Product Updated<br>";
			 	} catch (Exception $ex) {
				echo $ex->getMessage();
				echo $content->product_sku." Error Product Not Updated<br>";
			}
			 // try to save ends
			}
			else
			{
				echo $content->product_sku." Not Found Product Not Updated<br>";
			}
exit;
			}
			}
?>