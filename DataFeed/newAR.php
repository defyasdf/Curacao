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
	
	//$credit = $proxy->WebCustomerApplication('sfakjlkame','sljfllkjl','l','3234789654','sanjay@gmail.com','1625 W. Olympic blvd', 'Los Angeles','CA','90015','3235595459','755-75-4587','e2212345689','DL', 'somename','company','8001543654','1965-06-15T12:00:00','2015-06-15T12:00:00','10','10','100000','CA','173.240.126.254','E','0','');
	
$credit = $proxy->RevAcctQualify(array(
                                      	'CustomerID' => '53545246',
										'Amount' => '100'
                                        ),
                                     "http://www.lacuracao.com/LAC-eComm-WebServices", 
                                     "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
                                     false, null , 'rpc', 'literal');  
	
	

echo '<pre>';	
	print_r($credit);
echo '</pre>';	