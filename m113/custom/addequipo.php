<?php

ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set("memory_limit","1024M");
//DB settings
$server = '192.168.100.121';
$user = 'curacaodata';
$pass = 'curacaodata';
$db = 'icuracaoproduct';
$link = mysql_connect($server,$user,$pass);
mysql_select_db($db,$link);	

$err = 0;
$msg = '';
if(isset($_REQUEST['fname'])){
	if(trim($_REQUEST['fname'])!='' && trim($_REQUEST['lname'])!='' && trim($_REQUEST['el-email'])!='' && trim($_REQUEST['tel'])!=''){
								
		$sql = "INSERT INTO `elkomander` (`fname`, `lname`, `email`, `telephone`,`opt_in`) VALUES ('".$_REQUEST['fname']."', '".$_REQUEST['lname']."', '".$_REQUEST['el-email']."', '".$_REQUEST['tel']."','".$_REQUEST['opt_in']."')";	
	
		if(!mysql_query($sql)){
			$err = 1;
			$msg = 'Data not inserted review your entry';
		}
	}else{
		$err = 1;
		$msg = 'All the information is required';
	}
}else{
	$err = 1;
	$msg = 'All the information is fname required';
}

if($err>0){
	echo $msg;
}else{
	echo '1';
}