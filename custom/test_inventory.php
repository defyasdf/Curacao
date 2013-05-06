<?php
ini_set('max_execution_time', 0);
//DB settings
$server = '192.168.100.121';
$user = 'curacaodata';
$pass = 'curacaodata';
$db = 'icuracaoproduct';
$link = mysql_connect($server,$user,$pass);
mysql_select_db($db,$link);	


$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
$ns = 'http://lacuracao.com/WebServices/eCommerce/';

//set the headers values

$headerbody = array('UserName' => 'mike', 
                    'Password' => 'ecom12'); 

//Create Soap Header.        
$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
        
//set the Headers of Soap Client. 
$h = $proxy->__setSoapHeaders($header); 

$credit = $proxy->InventoryLevel(array('cItem_ID'=>'33R-E27-SVL24112FXB','cLocations'=>'09,06,01,16,22,29,35,38,51'));

$result = $credit->InventoryLevelResult;


$s = explode("\\",$result);
$tot = 0;

print_r($s);
exit;
for($i=0;$i<(sizeof($s)-1);$i++){
	$inv = explode("|",$s[$i]);
	if($inv[1]-2>=1){
		$tot += $inv[1]-2;
	}
}
$sql = "update curacao_skus set qty = ".$tot." where product_id = ".$row['product_id'];
if(mysql_query($sql)){
	echo "SKU:".$row['curacao_sku']." QTY:".$tot."<br>";
}else{
	echo "not updated";
}


$query = "insert into inventorycronstatus(cronday, status) values('".date('Y-m-d')."','1')";
mysql_query($query) or die(mysql_error());