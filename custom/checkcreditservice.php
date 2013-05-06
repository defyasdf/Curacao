<?php

/// Final Step of the credit...		
		$server = '192.168.100.121';
		$user = 'curacaodata';
		$pass = 'curacaodata';
		$db = 'icuracaoproduct';
		
		
		$link = mysql_connect($server,$user,$pass);
		
		mysql_select_db($db,$link);

		/*$sql = "UPDATE `credit_app` SET `company` = '".$_POST['company']."', 
							`work_phone` = '".$_POST['warea'].$_POST['wlocal1'].$_POST['wlocal2']."', 
							`maiden_name` = '".$_POST['add1']."', 
							`work_year` = '".$_POST['eyears']."', 
							`work_month` = '".$_POST['emonths']."', 
							`salary` = '".$_POST['salary']."'
							 WHERE `credit_id` = ".$_POST['appid'];		
		mysql_query($sql);*/								
		
		$q = 'select * from credit_app where `credit_id` = 168350';
		$r = mysql_query($q);
		$customer = mysql_fetch_array($r);
	
		$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
		$ns = 'http://lacuracao.com/WebServices/eCommerce/';
		$headerbody = array('UserName' => 'mike',  'Password' => 'ecom12');
		
		//Create Soap Header.        
		$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
				
		//set the Headers of Soap Client. 
		$h = $proxy->__setSoapHeaders($header); 
		
		$dobs = explode("-", $customer['dob']);
		$exps = explode("-", $customer['id_expire']);
		
		$credit = $proxy->WebCustomerApplication(array(
								'LastName' => $customer['lastname'],
								'FirstName' => $customer['firstname'],
								'MiddleInitial' => $customer['middlename'],
								'HomePhone' => $customer['telephone'],
								'eMail' => $customer['email_address'],                                        
								'Street' => trim($customer['address1']) . ' ' . trim($customer['address2']), 
								'City' => $customer['city'],
								'State' => $customer['state'],
								'Zip' => $customer['zipcode'],
								'Phone2' => $customer['telephone2'],
								'SSN' => $customer['ssn'],
								'ID' => $customer['id_num'],
								'IDType' => $customer['id_type'],
								'MotherMaidenName' => $customer['maiden_name'],
								'WorkName' => $customer['company'],
								'WorkPhone' => $customer['work_phone'],
								'DOB' => date('Y-m-d\Th:i:s', mktime(0,0,0,$dobs[1], $dobs[2], $dobs[0])),
								'IDExpiration' => date('Y-m-d\Th:i:s',mktime(0,0,0,$exps[1], $exps[2], $exps[0])), 
								'LenghtInCurrAddress' => ($customer['res_year']*12+$customer['res_month']),
								'LenghtInCurrWork' => ($customer['work_year']*12+$customer['work_month']),
								'AnnualIncome' => $customer['salary'],
								'IDState' => empty($customer['id_state'])?$customer['state']:$customer['id_state'],
								'IPAddress' => $customer['ip_address'],
								'Language' => $customer['language'], 
								'AGPP' => $customer['aggp'] == 1 ? 'Y' : 'N',
								'Submit' => $customer['is_lexis_nexus_complete'] == 1 ? 'Y': 'N'
								),
							 "http://www.lacuracao.com/LAC-eComm-WebServices", 
							 "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
							 false, null , 'rpc', 'literal');  
	
		$re = $credit->WebCustomerApplicationResult;	
		$final = explode("|",$re);
	
		echo '<pre>';
			print_r($final);
		echo '</pre>';
	///End of credit portion
