<?php

$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
$ns = 'http://lacuracao.com/WebServices/eCommerce/';

//set the headers values

$headerbody = array('UserName' => 'mike', 
                    'Password' => 'ecom12'); 

//Create Soap Header.        
$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
        
//set the Headers of Soap Client. 
$h = $proxy->__setSoapHeaders($header); 

$credit = $proxy->InventoryLevel(array('cItem_ID'=>'069-Q19-1002','cLocations'=>'16'));

$result = $credit->InventoryLevelResult;

$s = explode("\\",$result);
$tot = 0;
print_r($s);
for($i=0;$i<(sizeof($s)-1);$i++){
	$inv = explode("|",$s[$i]);
	if($inv[1]-2>=1){
		$tot += $inv[1]-2;
	}
}
if(sizeof($s)>1){
	echo 'sku found';
}else{
	echo 'sku didnt found';
}

echo $tot;
