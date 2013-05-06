<?php

	// INI setting
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");

	//server DB connection
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';

	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);	

	$query = 'select * from arapiuser where username = "'.securepass($_REQUEST['username']).'" AND pass = "'.securepass($_REQUEST['password']).'"';
	$res = mysql_query($query);
	$num_rows = mysql_num_rows($res);
	if($num_rows>0){
	
	//Magento Mage connection

	$mageFilename = '../app/Mage.php';
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	umask(0);
	Mage::app('default'); 
	//Getting current store ID	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
	$product = Mage::getModel('catalog/product');
	$productId = $product->getIdBySku($_REQUEST['currentsku']);
	if($productId){
		
		$product->load($productId);
		$product->setSku($_REQUEST['newsku']);
		try {
			$product->save();
			echo 'SUCCESS';
		}
		catch (Exception $ex) {
			echo $ex->getMessage();
		}
		
	}else{
		echo 'Product Not Found';
	}
	
	
	}else{
		echo 'Authentication Failed';
	}
	
	function securepass($pass){
		
		return md5($pass.'curacaosecurity');
	} 
	