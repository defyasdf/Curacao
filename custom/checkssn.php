<?php 
	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	$headerbody = array('UserName' => 'mike', 
						'Password' => 'ecom12'); 
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
			
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
	
	$credit = $proxy->HasSSN(array(
											'CustID' => $_REQUEST['custid']
											
											),
										 "http://www.lacuracao.com/LAC-eComm-WebServices", 
										 "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
										 false, null , 'rpc', 'literal');
	
								 
	
	if($credit->HasSSNResult){
		echo '1';
	}else{
		echo '0';
	}
?>