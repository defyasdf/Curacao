<?php 

	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	
	//
	
	$headerbody = array('UserName' => 'mike', 
						'Password' => 'ecom12'); 
	
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
			
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
	
	$credit = $proxy->ValidateDP(array(
                                      	'CustID' => '5864577',
                                        'DOB' => '1984-03-20',
										'Zip' => '',
										'SSN' => '',
										'MMaiden' => '',
										'Amount' => '200'
                                        ),
                                     "http://www.lacuracao.com/LAC-eComm-WebServices", 
                                     "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
                                     false, null , 'rpc', 'literal');  
	
	
$result =  $credit->ValidateDPResult;

echo '<pre>';
	print_r($result);
echo '</pre>';

echo $result->DownPayment;