<?php 

	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_magento';
	$link = mysql_connect($server,$user,$pass);
	mysql_select_db($db,$link);	

	$sql = 'SELECT * FROM `customer_entity` where email = "sanjay@gmail.com"';
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	
	$now = time(); // or your date as well
	$your_date = strtotime($row['created_at']);
	$datediff = $now - $your_date;
	echo floor($datediff/(60*60*24));

?>