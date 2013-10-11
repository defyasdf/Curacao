<?php

	require_once('./_includes/ini_settings.php');
	require_once('./_includes/db_config.php');
	require_once('./_includes/mage_head.php');

	$link = mysql_connect($server,$user,$pass);
	mysql_select_db('curacao_magento',$link);	
	
	$link1 = mysql_connect($server,$user,$pass,true);
	mysql_select_db('icuracaoproduct',$link1) or die("No DB");
	
	$query = "SELECT * FROM `preapproved` WHERE step2 = '1'";
	$re = mysql_query($query,$link1) or die(mysql_error());
	
	$data = array();
	
	while($row1 = mysql_fetch_array($re)){
	
		$q = "select * from customer_entity where email = '".$row1['email']."'";
		$res = mysql_query($q,$link);
		$r = mysql_fetch_array($res);
	
		$sql = "SELECT * FROM `sales_flat_order` WHERE `customer_id` = '".$r['entity_id']."'";
		$result = mysql_query($sql,$link);
		//$row = mysql_fetch_array($result);
		$total = 0;
		$curacao_credit = 0;
		$num = mysql_num_rows($result);
		if($num>0){
			while($row = mysql_fetch_array($result)){
				$total += $row['grand_total'];
				$curacao_credit += $row['curacaocustomerdiscount'];
				if($num>0){
					$status = $row['status'];
				}else{
					$status = '';
				}
	
			}
		}else{
			$status = '';
		}
	
		if(strtoupper(substr($row1['promo'],0,2)) == 'AD'){
			$promo = 'Apple';
		}else{
			$promo = "Online";
		}
	
		$data[] = array("Promo_Code"=>$row1['promo'],"Customer_Name"=>$row1['fname'].' '.$row1['lname'],"Customer_email"=>$row1['email'],"Campaign"=>$promo,"No_of_order"=>$num,"Total_purchase"=>$total,"Curacao_credit"=>$curacao_credit,"Order_Status"=>$status,"Create_Date"=>$row1['created_date']);
	
	}
	
	
	$filename = "preapproved_Purcahse_history.xls";
	
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
	
	mysql_close($link);
	mysql_close($link1);
	
	exit;
	