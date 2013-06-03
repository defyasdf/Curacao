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

$sql = "select * from preapproved";
$result = mysql_query($sql);
$data = array();
while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
	array_push($data, $row);
}


 $filename = "preapprove.xls";
	
	  header("Content-Disposition: attachment; filename=\"$filename\"");
	  header("Content-Type: application/vnd.ms-excel");
	
	  $flag = false;
	  foreach($data as $row) {
	
		if(!$flag) {
		  // display field/column names as first row
		  echo implode("\t", array_keys($row)) . "\r\n";
		  $flag = true;
		}
	   // array_walk($row, 'cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	  }
	
	
	exit;	