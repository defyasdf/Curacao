<?php
	
	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
 

	$rule = Mage::getModel('salesrule/rule')->load(256); 
	$conditions = $rule->getConditions()->asArray();
	
	echo '<pre>';
		print_r($conditions);
	echo '</pre>';