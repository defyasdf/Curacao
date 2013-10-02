<?php
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","2048M");
	ini_set('apc.cache_by_default', 0); 
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);	
	
	$dT = explode('/',$_REQUEST['edate']);
	$dF = explode('/',$_REQUEST['sdate']);
	
	if(trim($_REQUEST['edate'])!=''){
		$to = $dT[2].'-'.$dT[0].'-'.$dT[1];
	}
	
	if(trim($_REQUEST['sdate'])!=''){
		$from = $dF[2].'-'.$dF[0].'-'.$dF[1];
	}
	
	if(trim($_REQUEST['sdate'])!='' && trim($_REQUEST['edate'])!=''){
		$sql = "SELECT * 	FROM `curacao_cust_tracking`	WHERE `checkoutdate` >= '".$from."'	and `checkoutdate` <= '".$to."'";
	}else{
		$sql = 'SELECT * FROM `curacao_cust_tracking`';
	}
	$result = mysql_query($sql);
	$data = array();
	while($row = mysql_fetch_assoc($result)){
		$data[] = $row;
	}
	
	 $filename = "customer_authenticatio_data.xls";
	
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