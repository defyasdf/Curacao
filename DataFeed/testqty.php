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
	
	$credit = $proxy->ItemQtyAvail('03M-E24-CRL32102','01,06');
	
	echo '<pre>';
		print_r($credit);
	echo '</pre>';

	/*$r = get_object_vars($credit->ItemQtyAvailResult);
	
	echo '<pre>';
		print_r($r);
	echo '</pre>';*/
?>