<?php

	include_once '_includes/db_config_mage.php';
	include_once '_includes/mage_head.php';
	$select = "select * from sales_flat_order where status = 'processestimate'";
	$res = mysql_query($select);
	while($row = mysql_fetch_array($res)){
		$order = Mage::getModel('sales/order')->load($row['entity_id']);
		$order->addStatusToHistory('processing', 'Approved By finance to be processed by Automated Script', false);
		$order->save();	
	}