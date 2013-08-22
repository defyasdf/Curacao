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
			$contents = get_qty_data_real();

			while($content = mysql_fetch_object($contents))
			{
			echo $content->sku." ".$content->qty."<br>";

        	$product_id = Mage::getModel('catalog/product')->getIdBySku($content->sku);
			if($product_id)
			{
			// set URL key start

		
			$product = Mage::getModel('catalog/product');
			$product ->load($product_id);
			$stockData = $product->getStockData();
			if($content->qty>0){
				$stockData['qty'] = $content->qty;
				$stockData['is_in_stock'] = 1;
			}else{
				
			}
			$product->setStockData($stockData);
			
			 // try to save start
			 try {
				$product->save();
			 	} catch (Exception $ex) {
				echo $ex->getMessage();
			}
			 // try to save ends
			}
			else
			{
			echo "No need to do anything";
			}

			}
			}
?>