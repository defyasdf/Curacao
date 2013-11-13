<?php 

	require_once('./_includes/ini_settings.php');
	require_once('./_includes/db_config.php');
	
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
	
	mysql_close($link);