<?php

	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	$server = '192.168.100.121';
	$user = 'curacao_magento';
	$pass = '4nRGQqyQ4KGPEw7n';
	$db = 'curacao_magento';


	$query = 'SELECT `order_id`,`store_id`, `created_at`, `product_id`, `sku`, `name`, `qty_ordered`, `price`, `tax_amount` FROM `sales_flat_order_item` ';
	$re = mysql_query($query);
	while($row = mysql_fetch_array($re)){
		$data[] = array("order_id"=>$row['order_id'],"store_id"=>$row['store_id'],'created_at'=>$row['created_at'],'product_id'=>$row['product_id'],'sku'=>$row['sku'],'name'=>$row['name'],'qty_ordered'=>$row['qty_ordered'],'price'=>$row['price'],'tax_amount'=>$row['tax_amount']);
	}
	
	 $filename = "Magento_order_item.xls";
	
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