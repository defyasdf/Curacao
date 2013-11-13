<?php
	set_time_limit(0);
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	require_once('../dbw.php');
	require_once('../urls.php');
	require_once('Mfunctions.php');
	// $fpl_id = $_GET['fpl_id'];
	$result = true;
	
	if($result == TRUE)
	{
	require_once(MAGE_FILE_URL);
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
        $currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
			$contents = get_qty_data_api_dandh();

			while($content = mysql_fetch_object($contents))
			{
        	$product_id = Mage::getModel('catalog/product')->getIdBySku(trim($content->sku));
			if($product_id)
			{
			// set URL key start
			$product = Mage::getModel('catalog/product');
			$product->load($product_id);
			// inventry data update start
			if(is_numeric($content->qty) AND $content->qty != "~")
			{
			if($content->qty > 0)
			{
			$stockData = $product->getStockData();
			$stockData['qty'] = $content->qty;
			$stockData['is_in_stock'] = 1;
			$product->setVisibility(4);
			$product->setStatus(1);
			}
			else
			{
			$stockData = $product->getStockData();
			$stockData['qty'] = "0";
			$stockData['is_in_stock'] = 0;
			$product->setVisibility(1);
			$product->setStatus(2);
			}
			}
			$product->setStockData($stockData);
			// inventry data update ends
			if($dbdataarray['cat'] != "~" AND $dbdataarray['cat'] != "")
			{
			$cat = $product->getCategoryIds();
			$cat[] = $dbdataarray['cat'];
			$product->setCategoryIds($cat);
			}
			if($dbdataarray['cost'] != "~" AND $dbdataarray['cost'] != "")
			$product->setcost($dbdataarray['cost']);
			if($dbdataarray['retail'] != "~" AND $dbdataarray['retail'] != "")
			 $product->setPrice($dbdataarray['retail']);
			if($dbdataarray['specialprice'] != "~" AND $dbdataarray['specialprice'] != "")
			 $product->setspecial_price($dbdataarray['specialprice']);

			if($dbdataarray['fromspecial'] != "~" AND $dbdataarray['fromspecial'] != "")
			{
			 $fromdate = date('mm/dd/yyyy', strtotime($dbdataarray['fromspecial']));
			 $product->setspecial_from_date($dbdataarray['fromspecial']);
			 $product->setSpecialFromDateIsFormated(true);
			}
			if($dbdataarray['tospecial'] != "~" AND $dbdataarray['tospecial'] != "")
			{
			 $todate = date('mm/dd/yyyy', strtotime($dbdataarray['tospecial']));
			 $product->setspecial_to_date($dbdataarray['tospecial']);
			 $product->setSpecialToDateIsFormated(true);
			}
			if($dbdataarray['metakeywords'] != "~" AND $dbdataarray['metakeywords'] != "")
			{
			$metainfo = $product->getmeta_keyword();
			$newmeta = $metainfo." ".$dbdataarray['metakeywords'];
			$product->setmeta_keyword($newmeta);
			}
			 // try to save start
			 try {
				$product->save();
				$dataprocessed = updatedatainventryid($content->sku);
			 	} catch (Exception $ex) {
				echo $ex->getMessage();
			}
			 // try to save ends

			}
			else
			{
			echo $content->sku." Not Found<br>";
			}

			}
			echo "All Sku's Updated";
			}
?>