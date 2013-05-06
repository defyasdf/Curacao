<?php
	ini_set('max_execution_time', 300);

	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	
	//
	
	$headerbody = array('UserName' => 'mike', 
						'Password' => 'ecom12'); 
	
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
			
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
	
	$credit = $proxy->EComAcctCredit(array('cCust_ID'=>$_GET['custnum']));

	$result = $credit->EComAcctCreditResult;
	
	$cust_info = explode('|',$result);
	
	echo $cust_info[4];