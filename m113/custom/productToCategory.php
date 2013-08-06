<?php
	// INI setting
		ini_set('max_execution_time', 0);
		ini_set('display_errors', 1);
		ini_set("memory_limit","1024M");

	// DB settings
		$server = '192.168.100.121';
		$user = 'curacaodata';
		$pass = 'curacaodata';
		$db = 'curacao_magento';
		$link = mysql_connect($server,$user,$pass);
		mysql_select_db($db,$link) or die("No DB");	

	//Magento Class Setting		
		$mageFilename = '/var/www/html/app/Mage.php';
		require_once $mageFilename;
		Varien_Profiler::enable();
		Mage::setIsDeveloperMode(true);
		umask(0);
		Mage::app('default'); 
		$currentStore = Mage::app()->getStore()->getId();
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
	//Codding Logic
	
		