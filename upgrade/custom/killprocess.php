<?php
	
	$server = '192.168.100.121';
	$user = 'curacao_magento';
	$pass = '4nRGQqyQ4KGPEw7n';
	//$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	$result = mysql_query("SHOW FULL PROCESSLIST");
	echo mysql_num_rows($result);
	while ($row=mysql_fetch_array($result)) {
	
	//print_r($row);
	
	  $process_id=$row["Id"];
	  //echo $row["Command"];
	  if ($row["Command"] == 'Sleep' ) {
		$sql="KILL $process_id";
		mysql_query($sql);
	  }
	}
?>