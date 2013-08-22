<?php

ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set("memory_limit","1024M");
//DB settings
$server = '192.168.100.121';
$user = 'curacaodata';
$pass = 'curacaodata';
$db = 'cdc';
$link = mysql_connect($server,$user,$pass);
mysql_select_db($db,$link);	

$mageFilename = '/var/www/m113/app/Mage.php';
require_once $mageFilename;
Varien_Profiler::enable();
Mage::setIsDeveloperMode(true);
umask(0);
Mage::app('default'); 
//Getting current store ID	
$currentStore = Mage::app()->getStore()->getId();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$sql = "select * from directmagento_inventory_real";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)){
	$product_id = Mage::getModel('catalog/product')->getIdBySku($row['sku']);
	if($product_id){
		// set URL key start
		$product = Mage::getModel('catalog/product');
		$product ->load($product_id);
		if($row['qty']>0){
			$product->setVisibility(4);
			$product->setStatus(1);
			$product->setStockData(array('manage_stock'=>1, 'is_in_stock' => 1, 'qty' => $row['qty']));
			
		}else{
			$product->setVisibility(1);
			$product->setStatus(2);
			$product->setStockData(array('manage_stock'=>1, 'is_in_stock' => 0, 'qty' => 0));
		}
		 // try to save start
		 try {
				$product->save();
				echo $row['sku'].'&acute;s Quantity been changed to '.$row['qty'];
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
		 // try to save ends
	}
	else{
		echo "No need to do anything";
	}
}
