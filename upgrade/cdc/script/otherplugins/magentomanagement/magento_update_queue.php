<?php
	set_time_limit(0);
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	require_once('../dbw.php');


	require_once('../urls.php');
	require_once('Mfunctions.php');
	// $fpl_id = $_GET['fpl_id'];
	$result = TRUE;
	
	if($result == TRUE)
	{
	require_once(MAGE_FILE_URL);
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
        $currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
			
			$vendorsdatainque = get_data_template_datainqueue_masterproducttable();
			
			while($vendorspendingrow = mysql_fetch_object($vendorsdatainque))
			{
			$templateid = $vendorspendingrow->templateid;
			$templatestructure = get_product_content_template($vendorspendingrow->templateid);
			// echo "<pre>";
			// print_r($templatestructure);
			// echo "</pre>";

			$datareplacearray = array (
					'product_sku' => 'sku',
					'product_upc' => 'upc',
					'prduct_name' => 'Name',
					'short_description' => 'ShortDescription',
					'product_description' => 'Description',
					'product_cost' => 'cost',
					'product_retail' => 'Price',
					'product_msrp' => 'Price',
					'product_qty' => 'StockData',
					'product_inventory_level' => 'StockData',
					'product_brand' => 'tv_brand',
					'product_img_path' => '',
					'product_features' => 'spec001',
					'product_source' => 'vendorid',
					'product_specs' => 'spec001',
					'height' => '',
					'width' => '',
					'length' => '',
					'weight' => 'weight',
					'product_shipping' => 'shprate',
					'product_metatags' => 'meta_keyword',
					'product_metadescription' => 'meta_description',
					'eng_video' => 'productvideo',
					'spanish_video' => 'productvideo',
					'specialprice' => 'special_price',
					'specialfromdate' => 'special_from_date',
					'specialtodate' => 'special_to_date'
										);
			$tempstructurearray = array_filter(explode(',',$templatestructure));
			$structuresize = sizeof($tempstructurearray);
			$contents = get_data_update_queue_masterproducttable($templateid);

			
			while($content = mysql_fetch_array($contents))
			{

			echo $productvendorid = get_vendorid($content['product_source']);
echo "<br>";
echo $content['product_sku'];
echo "<br>";
        	$product_id = Mage::getModel('catalog/product')->getIdBySku(trim($content['product_sku']));
			if($product_id)
			{
			$product = Mage::getModel('catalog/product');
			$product->load($product_id);
			echo $productvendorfrommagento = $product->getvendorid();

			if($productvendorid == $productvendorfrommagento)
			{

							if(in_array('prduct_name',$tempstructurearray))
							{
							 $product->setName($content['prduct_name']);
							}
							if(in_array('product_category',$tempstructurearray))
							{
							 $cat = $product->getCategoryIds();
				
							$newarray = explode('_',$content['product_category']);
							$arraysize = sizeof($newarray);
							 if($arraysize > 1)
							 {
								for($i=0;$i<$arraysize;$i++)
								$cat[] = $newarray[$i];
								$cat = array_unique(array_filter($cat));
							 }
							 else
							 {
							 $cat[] = $content['product_category'];
							 $cat = array_unique(array_filter($cat));
							 }
							 $product->setCategoryIds($cat);
							}
							if(in_array('short_description',$tempstructurearray))
							{
							 $product->setShortDescription($content['short_description']);
							}
							if(in_array('product_description',$tempstructurearray))
							{
							 $product->setDescription($content['product_description']);
							}
							if(in_array('product_cost',$tempstructurearray))
							{
							 $product->setcost($content['product_cost']);
							}
							if(in_array('product_retail',$tempstructurearray))
							{
							 $product->setPrice($content['product_retail']);
							}
							if(in_array('product_qty',$tempstructurearray))
							{
							if($content['product_qty'] > 0 AND is_numeric($content['product_qty']) AND $content['product_qty'] != "")
							{
							 $product->setStockData(array('is_in_stock' => 1, 'qty' => $content['product_qty']));
							$product->setVisibility(4);
							$product->setStatus(1);
							}
							elseif($content['product_qty'] == "0" AND is_numeric($content['product_qty']))
							{
							$product->setStockData(array('is_in_stock' => 0, 'qty' => 0));
							$product->setVisibility(1);
							$product->setStatus(2);
							}else{
							$product->setStockData(array('is_in_stock' => 0, 'qty' => 0));
							$product->setVisibility(1);
							$product->setStatus(2);
								}
				
							}
							if(in_array('product_brand',$tempstructurearray))
							{
							 $product->settv_brand($content['product_brand']);
							}
							if(in_array('product_source',$tempstructurearray))
							{
							 $product->setvendorid($content['product_source']);
							}
							if(in_array('product_specs',$tempstructurearray))
							{
							 $product->setspec001($content['product_specs']);
							}
							if(in_array('weight',$tempstructurearray))
							{
							 $product->setweight($content['weight']);
							}
							if(in_array('product_shipping',$tempstructurearray))
							{
							$product->setshprate($content['product_shipping']);
							}
							if(in_array('product_metatags',$tempstructurearray))
							{
							 $product->setmeta_keyword(htmlspecialchars_decode($content['product_metatags']));
							}
							if(in_array('product_metadescription',$tempstructurearray))
							{
							 $product->setmeta_description(htmlspecialchars_decode($content['product_metadescription']));
							}
							if(in_array('eng_video',$tempstructurearray))
							{
							 $eng_video = str_replace($search, $values, $content['eng_video']);
							 $product->setproductvideo(htmlspecialchars_decode($eng_video,ENT_QUOTES));
							}
							if(in_array('specialprice',$tempstructurearray))
							{
							 $product->setspecial_price(htmlspecialchars_decode($content['specialprice']));
							}
							if(in_array('specialfromdate',$tempstructurearray))
							{
							 $product->setspecial_from_date(htmlspecialchars_decode($content['specialfromdate']));
							}
							if(in_array('specialtodate',$tempstructurearray))
							{
							 $product->setspecial_to_date(htmlspecialchars_decode($content['specialtodate']));
							}
							if(in_array('onlineonly',$tempstructurearray))
							{
							 $product->setset(htmlspecialchars_decode($content['onlineonly']));
							}
							
							
							
							
							
							 // try to save start trim($content['product_sku'])
							 try {
								$product->save();
								$dataprocessed = updateapimater_table_status(trim($content['product_sku']));
								echo $content['product_sku']." Updated<br>";
								
								} catch (Exception $ex) {
								echo $ex->getMessage();
							}
			 // try to save ends
			}
			else
			{
// 3 means products not belong to this vendor
			mysql_query("UPDATE `update_queue_masterproducttable` SET `status`=3 WHERE `product_sku`='$content[product_sku]'");
echo $content['product_sku']."-not belongs to";
exit;
			}
			}
			else
			{
			echo $content->sku." Not Found<br>";
			}
			}
			}
			echo "All Sku's Updated";
			}
?>