<?php
ini_set('max_execution_time', 300);
$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/test/eCommerce/Main.asmx?WSDL');
$ns = 'http://lacuracao.com/WebServices/eCommerce/';

//

$headerbody = array('UserName' => 'mike', 
                    'Password' => 'ecom12'); 

//Create Soap Header.        
$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
        
//set the Headers of Soap Client. 
$h = $proxy->__setSoapHeaders($header); 

$credit = $proxy->ItemQtyAvail(array('cItem_ID'=>'03M-E24-CS6124','cLocations'=>'01,06'));



$result = $credit->ItemQtyAvailResult;

$s = $result->any;

$a = json_decode(json_encode((array) simplexml_load_string($s)),1);



echo '<pre>';
	print_r($a);
echo '</pre>';
