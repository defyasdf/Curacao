<?php	

// INI Setting	
    ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
// End INI Setting	
// Server DB setting
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_production';
	$link = mysql_connect($server,$user,$pass);
	$link1 = mysql_connect($server,$user,$pass,true);
	mysql_select_db($db,$link) or die("No DB");	
	mysql_select_db('icuracaoproduct',$link1) or die("No DB");
// End Server DB settings
// Mage Class setting	
	$mageFilename = '/var/www/m113/app/Mage.php';	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	umask(0);
	Mage::app('default'); 
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
// End Mage class setting	
	
	$sql = 'SELECT * FROM `gwmtracking` WHERE quote_id != ""';
	$result = mysql_query($sql,$link1);
	$data = array();
	while($row = mysql_fetch_array($result)){
		$query = "SELECT * FROM `sales_flat_quote` WHERE `entity_id` = ".$row['quote_id'];
		$res = mysql_query($query,$link);
		$rw = mysql_fetch_array($res);
		$que = "SELECT * FROM `sales_flat_quote_item` WHERE `quote_id` = ".$row['quote_id'];
		$res1 = mysql_query($que,$link);
		while($rw1 = mysql_fetch_array($res1)){
			$product = Mage::getModel('catalog/product');
			$product->load($rw1['product_id']);
			$total = $rw1['price']+$product->getShprate();
			$data[] = array("Customer_Name"=>$rw['customer_firstname'].' '.$rw['customer_lastname'],"Customer_Email"=>$rw['customer_email'],"SKU"=>$rw1['sku'], "Item_count"=>$rw1['qty'],"SubTotal"=>$rw1['price'],"Shipping"=>$product->getShprate(), "Total"=>$total,"OrderGrandTotal"=>$rw['grand_total']);		
		}
		
				
		
	}
	
  $filename = "GWM_Order_info.xls";
	
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
