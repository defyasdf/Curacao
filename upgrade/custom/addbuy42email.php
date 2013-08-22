<?php 
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	mysql_select_db($db,$link);	

	$query = "select * from addbuy42email where email = '".$_POST['email']."'";
	$re = mysql_query($query);
	if(mysql_num_rows($re)>0){
		echo '2';
	}else{
		$sql = 'insert into addbuy42email (email,hid,sid,affid,created_date) values ("'.$_POST['email'].'","'.$_POST['hid'].'","'.$_POST['sid'].'","'.$_POST['affid'].'","'.date('Y-m-d').'")';
//		mysql_query($sql) or die(mysql_error());
		if(mysql_query($sql)){
			echo '1';
		}else{
			echo '0';
		}
	}