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
	
	$sql = "SELECT * FROM `sales_flat_order` WHERE `created_at` > '2013-09-22' and hid !=''";
	$result = mysql_query($sql);
	echo '<table border="1"><tr><td>Order Number</td><td>Hid</td><td>Affiliete Ids</td><td>SubTotal</td><td>Discount</td><td>Grand Total</td></tr>';
	while($row = mysql_fetch_array($result)){
		$sql1 = "SELECT * FROM `gwmtracking` WHERE hid = '".$row['hid']."'";
		$result1 = mysql_query($sql1);
		$data = array();
		while($row1 = mysql_fetch_array($result1)){
			$data[] = $row1['affid'];
		}
		$affid = implode(",",$data);
		
		echo '<tr><td>'.$row['increment_id'].'</td><td>'.$row['hid'].'</td><td>"'.$affid.'"</td><td>'.$row['subtotal'].'</td><td>'.$row['discount_amount'].'</td><td>'.$row['grand_total'].'</td></tr>';
		
		
	}
	
	echo '</table>';