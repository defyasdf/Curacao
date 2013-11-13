<?php

	echo str_replace($_SERVER['QUERY_STRING'],'',$_SERVER['REQUEST_URI']);
	echo '<br>';
	echo str_replace("___store=default","",$_SERVER['QUERY_STRING']);
	exit;
	include_once '_includes/db_config_mage.php';
	$select = "select * from storepickup_store where store_id = '".$_REQUEST['str_id']."'";
	$res = mysql_query($select);
	$row = mysql_fetch_array($res);
	
	echo '<p><h2>'.$row['store_name'].'</h2></p>';
	echo '<p>'.$row['address'].'</p>';
	echo '<p>'.$row['city'].'</p>';
	echo '<p>'.$row['state'].', '.$row['zipcode'].'</p>';
