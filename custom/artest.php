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

$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
$ns = 'http://lacuracao.com/WebServices/eCommerce/';

//set the headers values

$headerbody = array('UserName' => 'mike', 
                    'Password' => 'ecom12'); 

//Create Soap Header.        
$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
        
//set the Headers of Soap Client. 
$h = $proxy->__setSoapHeaders($header); 

$collection = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*') // select all attributes
		->addAttributeToFilter('shprate', 'Domestic')
		->setCurPage(4); // set the offset (useful for pagination)
		
		// we iterate through the list of products to get attribute values
		$j = 1;
foreach ($collection as $products) {

	

	

$credit = $proxy->InventoryLevel(array('cItem_ID'=>$products->getSku(),'cLocations'=>'06'));

$result = $credit->InventoryLevelResult;

$s = explode("\\",$result);
$tot = 0;

for($i=0;$i<(sizeof($s)-1);$i++){
	$inv = explode("|",$s[$i]);
	if($inv[1]-2>=1){
		$tot += $inv[1]-2;
	}
}
if(sizeof($s)>1){
$product = Mage::getModel('catalog/product');
$product->load($products->getId());

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

$sql = "update curacao_skus set qty = ".$tot." where product_id = ".$product->getId();
if(mysql_query($sql)){
	echo "SKU:".$products->getSku()." QTY:".$tot."<br>";
}else{
	echo "not updated";
}
}
}
}
$query = "insert into inventorycronstatus(cronday, status) values('".date('Y-m-d')."','1')";
mysql_query($query) or die(mysql_error());