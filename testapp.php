<?php 
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);


	$q = 'select * from credit_app where `credit_id` = 166544';
	$r = mysql_query($q);
    $customer = mysql_fetch_array($r);

	
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
//	 $dobs = explode("-", $customer['dob']);
	// $exps = explode("-", $customer['id_expire']);
	 
	 $dobs = $customer['dob'];
	 $exps = $customer['id_expire'];
	 
	 //echo date('Y-m-d\Th:i:s', mktime(0,0,0,$dobs[1], $dobs[2], $dobs[0]));
	 
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
                                        'DOB' => $dobs.'T00:00:00',
                                        'IDExpiration' => $exps.'T00:00:00', 
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


print_r($final);
?>