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
	$mageFilename = '/var/www/html/app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
	$sql = 'SELECT * FROM `table 1`';
	$resu = mysql_query($sql);
	$i = 1;
	while($row = mysql_fetch_array($resu)){

		$_product = Mage::getModel('catalog/product');
		$_product->load($row['product_id']);
		
		$_product->setLg_webcollage('1');
		
		try {
			
		    $_product->save();
			echo $i.' Product has been updated in english ';
			
		}
		catch (Exception $ex) {
		    echo $ex->getMessage();
		}
		
		$i++;

	}
	

	 
	 //Email Stop