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

	$sql = "SELECT distinct(parent_id), status  FROM `sales_flat_order_status_history` WHERE status = 'internalfulfillment' or status = 'senttovendor' and `created_at` >= '2013-08-01 00:00:00'";

	$result = mysql_query($sql);
	echo '<table border="1"><tr><td>Order Number</td><td>Customer #</td><td>Initial Status</td><td>Status change</td><td>Current status</td><td>Sub Total</td><td>Shipping</td><td>Tax</td><td>Discount</td><td>Grand Total</td></tr>';
	while($row = mysql_fetch_array($result)){
		$ssql = 'select * from sales_flat_order_status_history where parent_id = '.$row['parent_id'];
		$sres = mysql_query($ssql);
		$srow = mysql_fetch_array($sres);
		$inistatus = $row['status'];
		$inst = '';
		while($st = mysql_fetch_array($sres)){
			//echo $st['status'];
			if($st['status'] == 'internalfulfillment' || $st['status'] == 'senttovendor' || $st['status'] == 'downpaymentrequired'){
				$inst = $st['status'];
			}
		
		}
		$osql = 'select * from sales_flat_order where entity_id = '.$row['parent_id'];
		$ores = mysql_query($osql);
		$orow = mysql_fetch_array($ores);
		echo '<tr><td>'.$orow['increment_id'].'</td><td>'.$orow['curacaocustomernumber'].'</td><td>'.$inistatus.'</td><td>'.$inst.'</td><td>'.$orow['status'].'</td><td>'.$orow['subtotal'].'</td><td>'.$orow['shipping_amount'].'</td><td>'.$orow['tax_amount'].'</td><td>'.$orow['discount_amount'].'</td><td>'.$orow['grand_total'].'</td></tr>';
	}
	echo '</table>';
	echo '<hr>';