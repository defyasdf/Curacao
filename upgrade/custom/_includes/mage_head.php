<?php
	
	$mageFilename = '../app/Mage.php';
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	umask(0);
	Mage::app('admin'); 

	//Mage::app()->cleanCache();