<?php

	require_once('./_includes/ini_settings.php');
	require_once('./_includes/db_config.php');

	$err = 0;
	$msg = '';
	
	if(isset($_REQUEST['fname'])){
		if(trim($_REQUEST['fname'])!='' && trim($_REQUEST['lname'])!='' && trim($_REQUEST['el-email'])!='' && trim($_REQUEST['tel'])!=''){
									
			$sql = "INSERT INTO `pepe` (`fname`, `lname`, `email`, `telephone`,`opt_in`) VALUES ('".$_REQUEST['fname']."', '".$_REQUEST['lname']."', '".$_REQUEST['el-email']."', '".$_REQUEST['tel']."','".$_REQUEST['opt_in']."')";	
		
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
	
	mysql_close();