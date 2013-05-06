<?php 
	
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("wsdl_cache_enabled","0");

	
	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	$headerbody = array('UserName' => 'mike', 
						'Password' => 'ecom12'); 
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
			
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
	
	$credit = $proxy->HasSSN(array(
									'CustID' => '5864577'
									),
										 "http://www.lacuracao.com/LAC-eComm-WebServices", 
										 "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
										 false, null , 'rpc', 'literal');
	
	/*$credit = $proxy->HasSSN(array(
									'CustID' => '5864577'
									),
										 "http://www.lacuracao.com/LAC-eComm-WebServices", 
										 "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
										 false, null , 'rpc', 'literal');*/
	print_r($credit);
	if($credit){
		echo 'something';
	}							 
	
	if($credit->HasSSNResult){
		
		echo '1';
	}else{
		echo '0';
	}

?>