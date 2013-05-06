<?php
	error_reporting(E_ALL | E_STRICT);

	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	ini_set('max_execution_time', 800);	
	
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
	
	//$fields = split(",",$readfile[$i]);
	$category = Mage::getModel('catalog/category');
	$category->load('8');
	$category->setName('Electonic');
	
	try {
		
		$category->save();
		echo 'updated';
		
	}
	catch (Exception $ex) {
		echo $ex->getMessage();
	}
	
	/*
	
	$product = Mage::getModel('catalog/product');
	
	$product->load($row['magento_pro_id']);		//field 0 == product_id
	$cagegory_id = $row['main_parent_id'];		//field 1 == category_id
	
	// then set product's category
	$product->setCategoryIds(array($cagegory_id,$row['magentocat_id']));
	
	try {
		
		$product->save();
		
		$q = 'update finalproductlist set `inmagento` = 1 where magento_product_id = '.$row['magento_pro_id'];
		mysql_query($q);
		
		echo $row['magento_pro_id'].' : Added To '.$row['main_parent_id'];
	}
	catch (Exception $ex) {
		echo $ex->getMessage();
	}
*/
	
	
?>