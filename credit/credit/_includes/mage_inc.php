<?php
	$mageFilename = '../app/Mage.php';	
	require_once $mageFilename;
	
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	ini_set('display_errors', 1);

	Mage::app('default');
	
	umask(0);	
?>