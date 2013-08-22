<?php 

	ini_set('max_execution_time', 600);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
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
	
	
	$sql = "select * from shipping_table2";
	$re = mysql_query($sql);
	$cnt = 1;
	$cnt1 = 0;	
	while($row = mysql_fetch_array($re)){

		$product = Mage::getModel('catalog/product');
		$product->load($row['magento_product_id']);
		$product->setShprate($row['shipping_rate']);	
		try {
			
		    $product->save();
			echo $row['magento_product_id'].' Product added shipping rate at '.$row['shipping_rate'];
			$cnt++;
			
		}
		catch (Exception $ex) {
		    echo $ex->getMessage();
			$cnt1++;
		}

		
	}
	
	echo $cnt." Products has been updated and ".$cnt1." Products has been skipped";
	