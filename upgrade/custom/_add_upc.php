<?php
ini_set('max_execution_time', 0);
//DB settings
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
//Getting current store ID	
$currentStore = Mage::app()->getStore()->getId();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


$sql = "SELECT * FROM `TABLE 53`";
$re = mysql_query($sql);
$cnt = 1;
$cnt1 = 0;	

while($row = mysql_fetch_array($re)){


$product = Mage::getModel('catalog/product');
$product->load($row['product_id']);


	$product->setUpc($row['upcs']);

try {
		
		$product->save();
		echo 'Product Updated successfully';		
		
}
catch (Exception $ex) {
	echo $ex->getMessage();
	
}

}
