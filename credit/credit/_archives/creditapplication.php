<?php
	ini_set('max_execution_time', 300);
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);
	
	$state = '';
	if($_POST['id_type']=='AU1'||$_POST['id_type']=='AU2'){
		$state = 'CA';
	}else{
		$state = $_POST['id_state'];
	}

	

	if($_POST['appid']==0){
		$sql = "INSERT INTO `credit_app` (`firstname`, `lastname`, `email_address`, `telephone`, `address1`, `address2`, `city`, `state`, `zipcode`, `res_year`, `res_month`, `telephone2`, `ssn`, `dob`, `id_num`, `id_type`, `id_country`, `id_state`, `id_expire`,  `maiden_name`, `company`, `work_phone`, `work_year`, `work_month`, `applied_date`, `ip_address`, `salary`, `aggp`, `is_lexis_nexus_complete`, `is_validate_address_complete`, `is_web_customer_application_complete`, `is_registration_complete`, `language`) VALUES ('".$_POST['fname']."', '".$_POST['lname']."','".$_POST['emailid']."', '".$_POST['area'].$_POST['local1'].$_POST['local2']."', '".$_POST['add1']."', '".$_POST['add2']."', '".$_POST['city']."', '".$_POST['state']."', '".$_POST['zip']."', '".$_POST['years']."', '".$_POST['months']."', '".$_POST['cphone1'].$_POST['cphone2'].$_POST['cphone3']."', '".$_POST['ssn1'].$_POST['ssn2'].$_POST['ssn3']."', '".$_POST['dobY'].'-'.$_POST['dobM'].'-'.$_POST['dobD']."', '".$_POST['idnumber']."', '".$_POST['id_type']."', 'USA', '".$state."','".$_POST['idexpY'].'-'.$_POST['idexpM'].'-'.$_POST['idexpD']."', '', '', '', '', '', '".date('Y-m-d')."', '".$_SERVER['REMOTE_ADDR']."', '', '0', '0', '0', '0', '0', '')";
		
		mysql_query($sql);
		
		echo mysql_insert_id();
	}else{
	
	
	$sql = "UPDATE `credit_app` SET `firstname` = '".$_POST['fname']."', 
													  `lastname` = '".$_POST['lname']."', 
													  `telephone` = '".$_POST['area'].$_POST['local1'].$_POST['local2']."', 
													  `address1` = '".$_POST['add1']."', 
													  `address2` = '".$_POST['add2']."', 
													  `city` = '".$_POST['city']."', 
													  `state` = '".$_POST['state']."', 
													  `zipcode` = '".$_POST['zip']."', 
													  `res_year` = '".$_POST['years']."', 
													  `res_month` = '".$_POST['months']."', 
													  `telephone2` = '".$_POST['cphone1'].$_POST['cphone2'].$_POST['cphone3']."', 
													  `ssn` = '".$_POST['ssn1'].$_POST['ssn2'].$_POST['ssn3']."', 
													  `dob` = '".$_POST['dobY'].'-'.$_POST['dobM'].'-'.$_POST['dobD']."', 
													  `id_num` = '".$_POST['id_number']."', 
													  `id_type` = '".$_POST['id_type']."', 
													  `id_expire` = '".$_POST['idexpY'].'-'.$_POST['idexpM'].'-'.$_POST['idexpD']."', 
													  `id_state` = '".$state."'
													  WHERE `credit_id` = ".$_POST['appid'];
		
		mysql_query($sql);
		
		echo $_POST['appid'];

	}	

?>
