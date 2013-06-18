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

/*$credit = $proxy->ECCreditLimit(array('Cust_ID'=>$_GET['custnum']));

$result = $credit->ECCreditLimitResult;

echo $result;
*/

$credit = $proxy->EComAcctCredit(array('cCust_ID'=>$_GET['custnum']));

$result = $credit->EComAcctCreditResult;

$cust_info = explode('|',$result);

echo $cust_info[8];

/*echo '<pre>';
	print_r($credit);
echo '</pre>';
*/