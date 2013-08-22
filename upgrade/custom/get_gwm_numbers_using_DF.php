<?php 

	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
// Server DB setting
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_magento';
	
	$link = mysql_connect($server,$user,$pass);
	$link1 = mysql_connect($server,$user,$pass,true);
	mysql_select_db($db,$link) or die("No DB");	
	mysql_select_db('icuracaoproduct',$link1) or die("No DB");

	$sql = "SELECT * FROM `gwmtracking` WHERE `created_date` > '2013-08-19' AND quote_id != ''";
	$re = mysql_query($sql,$link1);
	$i = 1;
	while($row = mysql_fetch_array($re)){
		$quote = 'select * from sales_flat_quote where entity_id = '.$row['quote_id'];
		$qr = mysql_query($quote,$link);
		$qro = mysql_fetch_array($qr);
		
		$order = 'select * from sales_flat_order where increment_id = "'.$qro['reserved_order_id'].'"';
		$or = mysql_query($order,$link) or die(mysql_error);
		if(mysql_num_rows($or)>0){
			$oro = mysql_fetch_array($or);	
			echo $i.' '.$oro['increment_id'].'<br>';
			$i++;
		}
		
	}	
	