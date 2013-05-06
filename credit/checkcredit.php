<?php 
	
	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	// Set headers
	$headerbody = array('UserName' => 'mike', 
						'Password' => 'ecom12'); 
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
			
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
	
	$credit = $proxy->RevAcctQualify(array('CustomerID'=>$_GET['cust_num'],'Amount'=>$_GET['amount']));
	
	$result = $credit->RevAcctQualifyResult;
	
	$r = explode('|',$result);
	
	echo $r[1].','.$r[3];
	
?>

