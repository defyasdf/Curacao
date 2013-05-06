<?php 

		$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
		$ns = 'http://lacuracao.com/WebServices/eCommerce/';
		$headerbody = array('UserName' => 'mike',  'Password' => 'ecom12');
		
		//Create Soap Header.        
		$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
				
		//set the Headers of Soap Client. 
		$h = $proxy->__setSoapHeaders($header); 
		
		
		$credit = $proxy->WebCustomerApplication(array(
								'LastName' => 'something',
								'FirstName' => 'something',
								'MiddleInitial' => '',
								'HomePhone' => '(222)111-2222',
								'eMail' => 'teast@test.com',                                        
								'Street' => 'Test', 
								'City' => 'Test',
								'State' => 'CA',
								'Zip' => '90015',
								'Phone2' => '',
								'SSN' => '1111552220',
								'ID' => 'A1122222',
								'IDType' => 'CA',
								'MotherMaidenName' => 'something',
								'WorkName' => 'something',
								'WorkPhone' => '1111111111',
								'DOB' => '1987-12-12',
								'IDExpiration' => '2014-01-01', 
								'LenghtInCurrAddress' => '60',
								'LenghtInCurrWork' => '60',
								'AnnualIncome' => '4000',
								'IDState' => 'CA',
								'IPAddress' => '',
								'Language' => 'English', 
								'AGPP' => 'N',
								'Submit' => 'Y'
								),
							 "http://www.lacuracao.com/LAC-eComm-WebServices", 
							 "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
							 false, null , 'rpc', 'literal');  
	
		$re = $credit->WebCustomerApplicationResult;	
		$final = explode("|",$re);
		
		print_r($final);