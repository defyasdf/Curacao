<?php

ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set("memory_limit","1024M");
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

$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
$ns = 'http://lacuracao.com/WebServices/eCommerce/';

//set the headers values

$headerbody = array('UserName' => 'mike', 
                    'Password' => 'ecom12'); 

//Create Soap Header.        
$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
        
//set the Headers of Soap Client. 
$h = $proxy->__setSoapHeaders($header); 

$sql = 'SELECT *  FROM `inventory16` WHERE `product_sku` LIKE "38S%"';
$r = mysql_query($sql);
while($ro = mysql_fetch_array($r)){

$credit = $proxy->InventoryLevel(array('cItem_ID'=>$ro['product_sku'],'cLocations'=>'16'));

$result = $credit->InventoryLevelResult;

$s = explode("\\",$result);
$tot = 0;
$storeinv = 0;
for($i=0;$i<(sizeof($s)-1);$i++){
	$inv = explode("|",$s[$i]);
	$sku = explode("-",$ro['product_sku']);
	$query = 'select * from thrashold16 where dept = "'.trim($sku[0]).'"';
	$res = mysql_query($query,$link);
	$num = mysql_num_rows($res);
	if($num>0){
		$row = mysql_fetch_array($res);
		$storeinv += $inv[1];
		
		if($row['thrashold'] == 'no' || trim($row['thrashold']) == ''){
			$tot = 0;
		}else{
			$thr = (int)$row['thrashold'];
			if($inv[1]-$thr>=1){
				$tot += $inv[1]-$thr;
			}
		}
	}
}

echo $sq = 'insert into inventory16(product_id, product_sku, storeinv, trashold, ecominv) values("'.$ro['product_id'].'","'.$ro['product_sku'].'","'.$storeinv.'","'.$row['thrashold'].'","'.$tot.'")';
//mysql_query($sq);
echo '<br>';
/*
if(sizeof($s)>1){

$product = Mage::getModel('catalog/product');

$product->load($products->getId());
//echo 'Fine';
//exit;
if($product->getInventorylookup()!=499){
	
if($tot==0){
	$product->setVisibility(1);
	$product->setStatus(2);
	$product->setStockData(array('manage_stock'=>1, 'is_in_stock' => 0, 'qty' => 0));
}else{
	$product->setVisibility(4);
	$product->setStatus(1);
	$product->setStockData(array('manage_stock'=>1, 'is_in_stock' => 1, 'qty' => $tot));
}
try {
		
		$product->save();
		
		
}
catch (Exception $ex) {
	echo $ex->getMessage();
	
}

//$sql = "update curacao_skus set qty = ".$tot." where product_id = ".$product->getId();
//if(mysql_query($sql)){
	echo "SKU:".$products->getSku()." QTY:".$tot."<br>";
//}else{
//	echo "not updated";
//}
}
}*/
}
$query = "insert into inventorycronstatus(cronday, status) values('".date('Y-m-d')."','1')";
mysql_query($query) or die(mysql_error());