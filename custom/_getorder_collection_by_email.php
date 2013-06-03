<?php

	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	//DB settings
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_magento';
	
	
	
	$link = mysql_connect($server,$user,$pass);
	$link1 = mysql_connect($server,$user,$pass,true);
	
	mysql_select_db($db,$link) or die("No DB");	
	mysql_select_db('icuracaoproduct',$link1) or die("No DB");	

	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('admin'); 
	
	$sql = 'select * from emailcamp';
	$resu = mysql_query($sql,$link1);
	while($rw = mysql_fetch_array($resu)){

		$q = "select * from customer_entity where email = '".$rw['email']."'";
		$res = mysql_query($q,$link);
		$r = mysql_fetch_array($res);
		 
		$sql = "SELECT * FROM `sales_flat_order` WHERE `customer_id` = '".$r['entity_id']."'";
		$result = mysql_query($sql,$link);
		//$row = mysql_fetch_array($result);
		$total = 0;
		$curacao_credit = 0;
		$date = '';
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
				$date = $row['created_at'];
			}
		}else{
				$status = '';
		}
			
		$data[] = array("Customer_email"=>$rw['email'],"No_of_order"=>$num,"Total_purchase"=>$total,"Curacao_credit"=>$curacao_credit,"Order_Status"=>$status, "Date"=>$date);
	}

	
	  $filename = "Magento_Order_by_email.xls";
	
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
