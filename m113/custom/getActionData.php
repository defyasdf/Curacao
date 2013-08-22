<?php
	// INI setting
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	
	//Magento Mage connection
	$mageFilename = '/var/www/m113/app/Mage.php';
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	umask(0);
	Mage::app('default'); 
	//Getting current store ID	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
	$rule = Mage::getModel('salesrule/rule')->load(8292); 
	$action = $rule->getActions()->asArray();
	echo $rule->getsimple_free_shipping();
	echo '<pre>';
		print_r($action);
	echo '</pre>';