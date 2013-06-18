<?php 

ini_set('max_execution_time', 300);
//Set the soap client
$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
$ns = 'http://lacuracao.com/WebServices/eCommerce/';
// Set headers
$headerbody = array('UserName' => 'mike', 
                    'Password' => 'ecom12'); 
//Create Soap Header.        
$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
        
//set the Headers of Soap Client. 
$h = $proxy->__setSoapHeaders($header); 

$credit = $proxy->IsCustomerActive(array('CustomerID'=>$_POST['custnum']));

$result = $credit->IsCustomerActiveResult;

$r = explode(';',$result);

if(strtolower($r[0])=='yes'){
	echo '1';
}else{
	echo '0';
}