<?php include_once('_includes/dictionary_eng.php');?>
<?php include_once('_includes/db.php'); ?>

<?php
	$sql = "UPDATE `credit_app` SET `company` = '".$_POST['company']."', 
								    `work_phone` = '".$_POST['warea'].$_POST['wlocal1'].$_POST['wlocal2']."', 
								    `maiden_name` = '".$_POST['add1']."', 
								    `work_year` = '".$_POST['eyears']."', 
								    `work_month` = '".$_POST['emonths']."', 
								    `salary` = '".$_POST['salary']."'
								     WHERE `credit_id` = ".$_POST['appid'];		
	mysql_query($sql);
		
	$q = 'select * from credit_app where `credit_id` = '.$_POST['appid'];
	$r = mysql_query($q);
    $customer = mysql_fetch_array($r);

	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	$headerbody = array('UserName' => 'mike',  'Password' => 'ecom12');
	
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
			
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
	
	//$credit = $proxy->WebCustomerApplication('sfakjlkame','sljfllkjl','l','3234789654','sanjay@gmail.com','1625 W. Olympic blvd', 'Los Angeles','CA','90015','3235595459','755-75-4587','e2212345689','DL', 'somename','company','8001543654','1965-06-15T12:00:00','2015-06-15T12:00:00','10','10','100000','CA','173.240.126.254','E','0','');
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
?>
<html>
	<head>
		<title>Credit Application</title>		
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/base.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/skeleton.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/styles.css" media="all" />
	</head>
	<body>
		<div class="container small">
	    	<div class="col twenty columns">	    	
<?php 
	if($final[0]=='SUCCESS'){
		$sql = "UPDATE `credit_app` SET `is_web_customer_application_complete` = '1'  WHERE `credit_id` = ".$_POST['appid'];
		mysql_query($sql);
?>
		     	<div align="right"><?php echo ACCNO?><?php echo $final[1]; ?></div>
			     <?php echo $final[0]?>
				<h1><?php echo CONGRATES?></h1>
				<p><?php echo APPROVE_1?><?php echo $final[2]?><?php echo APPROVE_2?></p>
<?php 
	} elseif($final[0]=='PENDING') {
?>
				<div align="right"><?php echo TMPNO?> <?php echo $final[1]; ?></div>
				<?php echo $final[0]?>
				<h2><?php echo THANKS?></h2>
				<p>	<?php echo PENDING?></p>
<?php
	} else { 
?>
				<?php echo $final[0]?>
				<h2><?php echo THANKS?></h2>
				<p><?php echo DECLINE?></p>
				<p><?php echo CUSTSERVICE?></p>
<?php
	}
?>
				<p><a href="index.php" target="_parent"><img src="_archives/shopnow.jpg"></a></p>
			</div>
		</div>
	</body>
</html>