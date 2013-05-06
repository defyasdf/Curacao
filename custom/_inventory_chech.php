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


$sql = "SELECT * FROM `table 4`";
$re = mysql_query($sql);
$cnt = 1;
$cnt1 = 0;	
$data = array();
while($row = mysql_fetch_array($re)){

$tot = $row['qty'];

$product = Mage::getModel('catalog/product');
$product->load($row['product_id']);

$data[] = array("Product_id"=>$product->getId(),"Excell_QTY"=>$row['qty'],"QTY_online"=>Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty());
	
}
 $filename = "Product_Qty_Check.xls";
	
	  header("Content-Disposition: attachment; filename=\"$filename\"");
	  header("Content-Type: application/vnd.ms-excel");
	
	  $flag = false;
	  foreach($data as $row) {
	
		if(!$flag) {
		  // display field/column names as first row
		  echo implode("\t", array_keys($row)) . "\r\n";
		  $flag = true;
		}
	   // array_walk($row, 'cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	  }
	
	
	exit;	
