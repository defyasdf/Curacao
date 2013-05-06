<?php 
	
	ini_set('max_execution_time', 300);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);	

	$sql1 = "SELECT * FROM `magentoque` where mstatus = 1";
	$resu1 = mysql_query($sql1);
	$num = 1;
	while($row1 = mysql_fetch_array($resu1)){	
	
		$sql = 'select * from finalproductlist where fpl_id = '.$row1['finalproId'];
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		
		$s = explode('-',$row['product_sku']);
		if($s[0]=='cur'){
			$sku = $s[2];
		}else{
			$sku = $row['product_sku'];
		}
		
	$data[] = array( "product_id"=>$row['fpl_id'],"name"=>$row['prduct_name'], "fullsku"=>$row['product_sku'], "sku"=>$sku, "UPC"=>$row['product_upc'], "source"=>$row['product_source'],"magento_category_ids"=>$row['magento_category_id']);
	
//			}
		}
		
	  $filename = "all_cats_product_data_" . date('Ymd') . ".xls";
	
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