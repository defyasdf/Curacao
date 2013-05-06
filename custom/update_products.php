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
	
	$sql = 'SELECT * FROM `table 2`';
	$resu = mysql_query($sql);
	$i = 1;
	while($row = mysql_fetch_array($resu)){

		$_product = Mage::getModel('catalog/product');
		$_product->load($row['pr_id']);
		if($row['status']=='Disable'){
			$_product->setVisibility(1);
			$_product->setStatus(2);
			$_product->setStockData(array('manage_stock'=>1, 'is_in_stock' => 0, 'qty' => 0));
		}else{		
			$_product->setCost($row['cost']);
			$_product->setVendorid($row['vendor_id']);
			$_product->setWeight($row['weight']);
		}
		try {
			
		    $_product->save();
			echo $i.' Product has been updated';
			
		}
		catch (Exception $ex) {
		    echo $ex->getMessage();
		}
		
		$i++;

	}
	

	 
	 //Email Stop