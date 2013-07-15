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
	
	$query = "select * from dellofferemail where email = '".$_POST['email']."'";
	$re = mysql_query($query);
	if(mysql_num_rows($re)>0){
		echo '2';
	}else{
				
		$sql = 'insert into dellofferemail (email,hid,sid,affid) values ("'.$_POST['email'].'","'.$_POST['hid'].'","'.$_POST['sid'].'","'.$_POST['affid'].'")';
		if(mysql_query($sql)){
			echo '1';
		}else{
			echo '0';
		}
	}