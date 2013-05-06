<?php
	
	ini_set('max_execution_time', 300);
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);
	
	if($_POST['edit_add'] == '1'){
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$street = $_POST['street'];
		$city  = $_POST['city'];
		$zip = $_POST['zip'];
		$state = $_POST['state'];
		
	}else{
		$fname = $_POST['prefname'];
		$lname = $_POST['prelname'];
		$street = $_POST['prestreet'];
		$city  = $_POST['precity'];
		$zip = $_POST['prezip'];
		$state = $_POST['prestate'];	
	}
	$ssn = substr($_POST['ssn'],0,1).$_POST['pressn'];
	$dob = $_POST['dobY'].'-'.$_POST['dobM'].'-'.$_POST['dobD'];
	$pphone = $_POST['pcode1'].$_POST['pcode2'].$_POST['pcode3'];
	$sphone = $_POST['code1'].$_POST['code2'].$_POST['code3'];
	if($_POST['appId']==0){
	
		$sql = "INSERT INTO `preapproved` (`promo`, `fname`, `lname`, `street`, `city`, `zip`, `state`, `pphone`, `sphone`, `ssn`,`dob`, `state_id`, `income`, `email`,  `addressverification`, `step1`, `step2`, `approve`, `pending`, `decline`, `shop`, `amount`, `checkout`, `order_complete`, `order_amount`, `ip_address`, `referal_url`, `created_date`) VALUES ('".$_POST['prepromo']."', '".$fname."', '".$lname."', '".$street."', '".$city."', '".$zip."', '".$state."', '".$pphone."', '".$sphone."', '".$ssn."','".$dob."', '".$_POST['state_id']."', '".$_POST['mIncome']."', '".$_POST['uemail']."',  '1', '0', '0', '0', '0', '0', '0', '', '0', '0', '','".$_SERVER['REMOTE_ADDR']."','".$_POST['referalurl']."','".date('Y-m-d')."')";
		
		mysql_query($sql);
		
		echo mysql_insert_id();
	}else{
	$step1 = '0';
	if(isset($_POST['state_id'])){
		$step1 = '1';
	}
	
	$sql = "UPDATE `preapproved` SET `fname` = '".$fname."', 
									 `lname` = '".$lname."', 
									  `pphone` = '".$pphone."', 
									  `street` = '".$street."', 
									  `dob`    = '".$dob."',	
									  `income`    = '".$_POST['mIncome']."',	
									  `email` = '".$_POST['uemail']."',
									  `city` = '".$city."', 
									  `state` = '".$state."', 
									  `zip` = '".$zip."', 
									  `sphone` = '".$sphone."', 
									  `ssn` = '".$ssn."', 
									  `state_id` = '".$_POST['state_id']."',
									  `step1` = '".$step1."'
									  WHERE `paID` = ".$_POST['appId'];
		
		mysql_query($sql);
		
		echo $_POST['appId'];

	}	

?>

