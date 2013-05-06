<?php
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	ini_set('max_execution_time', 0);	
	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);

	
	Mage::app('default');
	
	// Here is the magic
	// This initializes the session using a cookied named 'frontend' and returns the 'core' namespace session
	Mage::getSingleton('core/session', array('name' => 'frontend'));
	
	// This returns the 'customer' namespace session
	$session = Mage::getSingleton('customer/session');
	
	Zend_Debug::dump($session->toArray()); 
	Zend_Debug::dump($_SESSION);
	
//echo $myData;
?>
