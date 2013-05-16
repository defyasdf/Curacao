<?php 
/*		$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
		$ns = 'http://lacuracao.com/WebServices/eCommerce/';
		
		$headerbody = array('UserName' => 'mike', 
							'Password' => 'ecom12'); 
		//Create Soap Header.        
		$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
		//set the Headers of Soap Client. 
		$h = $proxy->__setSoapHeaders($header); 
		
		$credit = $proxy->ValidateDP(array(
											'CustID' => '53145246',
											'DOB' => '06/11/1984',
											'SSN' => '6188',
											'MMaiden' => '',
											'Amount' => '2000',
											'CCV' => '929'
											),
										 "http://www.lacuracao.com/LAC-eComm-WebServices", 
										 "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
										 false, null , 'rpc', 'literal');  
		
		
		$result =  $credit->ValidateDPResult;
	print_r($result);*/


	$url = 'http://108.171.160.207/custom/checkIP.php';
	$fields = array(	
						'CustID' => '53145246',
						'DOB' => '',
						'SSN' => '6188',
						'MMaiden' => '',
						'Amount' => '2000',
						'CCV' => '929'
					);
	$fields_string = '';
	//url-ify the data for the POST
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string, '&');
	
	//open connection
	$ch = curl_init();
	
	//set the url, number of POST vars, POST data
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	//execute post
	$result = curl_exec($ch);
//	$result = unserialize($result);
//	print_r(unserialize($result));
	
	echo $result;
	//close connection
	curl_close($ch);
	

	
	
?>
