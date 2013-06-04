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
 	
	$product = Mage::getModel('catalog/product');
	
	$product->load(7414);
	
	$cat_ids = $product->getCategoryIds();
	$cName = array();
	$cid = array();
	for($i = 0;$i<(sizeof($cat_ids));$i++){
		if(isset($cat_ids[$i])){
			$scat = $cat = Mage::getModel('catalog/category')->load($cat_ids[$i]);
			
			if(in_array($scat->parent_id,$cat_ids)){
				$cid[] = $scat->parent_id;
				$key = array_search($scat->parent_id, $cat_ids); // $key = 2;
				unset($cat_ids[$key]);
			}	
		}
		//$cName[] = Mage::getModel('catalog/category')->load($cat_ids[$i])->getName();
		
	}		
	$cattree = array_merge($cid,$cat_ids);	
	for($j=0;$j<sizeof($cattree);$j++){
		$cName[] = Mage::getModel('catalog/category')->load($cattree[$j])->getName();
	}
	
	$cat = implode('_',$cName);

	echo $cat;