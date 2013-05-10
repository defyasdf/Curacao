<?php
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","2048M");
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_magento';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);	
	
	$sql = "SELECT `grand_total`,`coupon_code`, `status` , `shipping_amount` , `shipping_canceled` , `shipping_invoiced` , `shipping_refunded` , `subtotal` , `subtotal_canceled` , `subtotal_invoiced` , `subtotal_refunded` , `tax_amount` , `tax_canceled` , `tax_invoiced` , `tax_refunded` , `total_canceled` , `total_invoiced` , `total_offline_refunded` , `total_online_refunded` , `total_paid` , `total_qty_ordered` , `total_refunded` , `increment_id` , `discount_description` , `gift_cards_amount` , `shipping_discount_amount` , `discount_amount` , `discount_canceled` , `discount_invoiced` , `discount_refunded`, `created_at`
FROM `sales_flat_order` 
where coupon_code != ''";


$result = mysql_query($sql);
$data = array();
while($row = mysql_fetch_assoc($result)){
	$data[] = $row;
}


 $filename = "Promo_code_report.xls";
	
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