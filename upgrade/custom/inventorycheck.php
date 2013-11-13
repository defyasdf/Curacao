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

$credit = $proxy->InventoryLevel(array('cItem_ID'=>'33F-P23-ZL810/01DU','cLocations'=>'09,06,01,16,22,29,35,38,51,33'));

$result = $credit->InventoryLevelResult;

echo '<pre>';
	print_r($result);
echo '</pre>';