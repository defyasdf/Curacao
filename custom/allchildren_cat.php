<?php
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","2048M");
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
 	
	
	
	$cat = Mage::getModel('catalog/category')->load(466);
	$subcatcollection = $cat->getChildren();
	
	$subcat = explode(",",$subcatcollection);
	
	$cat_collecton = $subcat;
	
	for($i=0;$i<sizeof($cat_collecton);$i++){
		$scat = $cat = Mage::getModel('catalog/category')->load($cat_collecton[$i]);
		$scatcollection = $scat->getChildren();
		if(trim($scatcollection)!=''){
			$sucat = explode(",",$scatcollection);
			$final_list = array_merge($cat_collecton,$sucat);
		}
	}
	
	print_r($final_list);