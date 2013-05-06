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

$data = $proxy->CheckPreAppPromo(array('PromoID'=>'eeffff4567'));

$result = $data->CheckPreAppPromoResult;


print_r($result);