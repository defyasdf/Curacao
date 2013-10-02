<?php

	// INI setting
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");

	//server DB connection
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';

	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);	

	$query = 'select * from arapiuser where username = "'.securepass($_REQUEST['username']).'" AND pass = "'.securepass($_REQUEST['password']).'"';
	$res = mysql_query($query);
	$num_rows = mysql_num_rows($res);
	if($num_rows>0){
	
	//Check if customer has ssn
	if($_REQUEST['ssnchk']=='yes'){
		$maiden = '';
		$ssn = $_REQUEST['ssn'];
	}else{
		$maiden = $_REQUEST['maiden'];
		$ssn = '';
	}
		$cnum = $_REQUEST['cnum'];
		$cvv = $_REQUEST['cvv'];
		$dob = $_REQUEST['dob'];	
		$amount = $_REQUEST['amount'];
	//validate the customer for down payment
	
	echo validate_customer_calculate_dp($cnum,$dob,$ssn,$maiden,$cvv,$amount);
	
	
	}else{
		echo '0:Authentication Failed';
	}
	
	
	function securepass($pass){
		
		return md5($pass.'curacaosecurity');
	} 
	
	function validate_customer_calculate_dp($cust_num,$dob,$ssn,$maiden,$ccv,$amount){
	  
	  	$cust_num = str_replace('-','',str_replace(' ','',$cust_num));
	 	 // Authentication, Authorization and Downpayment calculation
		
		
		$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
		$ns = 'http://lacuracao.com/WebServices/eCommerce/';
		
		$headerbody = array('UserName' => 'mike', 
							'Password' => 'ecom12'); 
		//Create Soap Header.        
		$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
				
		//set the Headers of Soap Client. 
		$h = $proxy->__setSoapHeaders($header); 
		
		$credit = $proxy->ValidateDP(array(
											'CustID' => $cust_num,
											'DOB' => $dob,
											'SSN' => $ssn,
											'MMaiden' => strtoupper($maiden),
											'Amount' => $amount,
											'CCV' => $ccv
											),
										 "http://www.lacuracao.com/LAC-eComm-WebServices", 
										 "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
										 false, null , 'rpc', 'literal');  
		
		
		$result =  $credit->ValidateDPResult;

		if(strtolower($result->StatusMessage) == 'ok'){
			$dp = $result->DownPayment;
		}else{
			$dp = $amount;
			$response = explode('[',$result->StatusMessage);
			if(strtolower($response[0]) == 'authentication error'){
				$auth = str_replace(']','',$response[1]);
				
			}else{
				$auth = 'credit_error';
			}
			
			
		}	
	
		return $dp.','.$auth;
	}