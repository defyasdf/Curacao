<?php 
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
	$sql = 'SELECT * FROM `specials` where status = 0';
	$resu = mysql_query($sql);
	while($row = mysql_fetch_array($resu)){

		
		
		$_product = Mage::getModel('catalog/product');
		$_product->setStoreId(1)->load($row['product_id']);
		$_product->load($row['product_id']);
	
		$cat = $_product->getCategoryIds();
		
		$c = explode(',',$row['category_id']);
		
		for($i=0;$i<sizeof($c);$i++){
			array_push($cat,$c[$i]);
		}
		
		if($row['cost']!=''){
			$_product->setCost($row['cost']);
		}
		if($row['price'] != ''){
			$_product->setPrice($row['price']);
		}
		if($row['special']!=''){
			$_product->setSpecialPrice($row['special']);
		}
		if($row['datefrom']!='0000-00-00'){
			$_product->setSpecialFromDate('2012-11-01');
			$_product->setSpecialFromDateIsFormated(true);
			
			$_product->setSpecialToDate('2012-11-22');
			$_product->setSpecialToDateIsFormated(true);
		}
		
		
		$_product->setCategoryIds($cat);		
		
		try {
			
		    $_product->save();
			echo ' Product has been updated in english ';
			
		}
		catch (Exception $ex) {
		    echo $ex->getMessage();
		}
		
		$_product->setStoreId(3)->load($row['product_id']);	
		
		//$_product->load($row['product_id']);
	
		$cat = $_product->getCategoryIds();
		
		$c = explode(',',$row['category_id']);
		for($i=0;$i<sizeof($c);$i++){
			array_push($cat,$c[$i]);
		}
		
		
		if($row['cost']!=''){
			$_product->setCost($row['cost']);
		}
		if($row['price'] != ''){
			$_product->setPrice($row['price']);
		}
		if($row['special']!=''){
			$_product->setSpecialPrice($row['special']);
		}
		if($row['datefrom']!='0000-00-00'){
			$_product->setSpecialFromDate('2012-11-01');
			$_product->setSpecialFromDateIsFormated(true);
			
			$_product->setSpecialToDate('2012-11-22');
			$_product->setSpecialToDateIsFormated(true);
		}
		
		$_product->setCategoryIds($cat);		
		
		try {
			
		    $_product->save();
			echo ' Product has been updated in spanish ';
			
		}
		catch (Exception $ex) {
		    echo $ex->getMessage();
		}	

	}