<?php

	error_reporting(E_ALL);
	
	ini_set('max_execution_time', 300);
	
	include 'reader.php';
    $excel = new Spreadsheet_Excel_Reader();
?>
 <?php
    $excel->read('/var/www/html/DataFeed/server/php/files/'.$_GET['file']);    
    $x=2;
	
	
	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct',$link) or die('not connecting');
	

//  echo $excel->sheets[0]['cells'][5][3];
//  exit;
//  echo $excel->sheets[0]['numRows'];
//  echo $excel->sheets[0]['numCols'];
  
  $process = 1;
  
  for($i=2;$i<=$excel->sheets[0]['numRows'];$i++){
	
	 $sql2 = "SELECT * FROM `masterproducttable` WHERE `product_sku` = '".$excel->sheets[0]['cells'][$i][3]."' and `product_source` = '".$excel->sheets[0]['cells'][$i][9]."'";
	 
	 $result = mysql_query($sql2);
	 
	 $num = mysql_num_rows($result);
	 
	 $sql1 = "SELECT * FROM `deletedproducts` WHERE `sku` = '".$excel->sheets[0]['cells'][$i][3]."' and `vendor` = '".$excel->sheets[0]['cells'][$i][9]."'";
	 
	 $result1 = mysql_query($sql1);
	 
	 $num1 = mysql_num_rows($result1);

		 if($num == 0 && $num1 ==0){ 	
		 $sql = "INSERT INTO `masterproducttable` (`prduct_name`, `product_description`, `product_sku`, `product_upc`, `product_inventory_level`, `product_brand`, `product_img_path`,`product_features`,`product_source`,`product_specs`, product_cost, product_retail, product_msrp, product_map, product_qty, priority, status) VALUES (";
			for($j=1;$j<=$excel->sheets[0]['numCols'];$j++){
				//echo '<div>'.$excel->sheets[0]['cells'][$i][$j].'</div>';
				
				$cell = trim($excel->sheets[0]['cells'][$i][$j]);
				
			
						$sql .= "'".htmlspecialchars(htmlspecialchars_decode($cell,ENT_QUOTES ),ENT_QUOTES )."',";
				
			}
			 $sql .= '"0")';
			
			 mysql_query($sql) or die("Record Not Inserted");
			 
			 
			 
			  $sql = "INSERT INTO `product_queue` (`prduct_name`, `product_description`, `product_sku`, `product_upc`, `product_inventory_level`, `product_brand`, `product_img_path`,`product_features`,`product_source`,`product_specs`, product_cost, product_retail, product_msrp, product_map, product_qty, priority, status) VALUES (";
			for($j=1;$j<=$excel->sheets[0]['numCols'];$j++){
				//echo '<div>'.$excel->sheets[0]['cells'][$i][$j].'</div>';
				
				$cell = trim($excel->sheets[0]['cells'][$i][$j]);

			
						$sql .= "'".htmlspecialchars(htmlspecialchars_decode($cell,ENT_QUOTES ),ENT_QUOTES )."',";

				
			}
			 $sql .= '"0")';
			
			 mysql_query($sql) or die("Record Not Inserted");
			 
			 
			 
			 $process++;
		 }
	 

	
 }
 	
	$total = $excel->sheets[0]['numRows']-1;
	
	$dup = $total - $process;
	
		echo '<h4 class="alert_success" style="margin:40px 1% 0">File has Processed Successfully: Out of '.$total.' records '.$dup.' records are duplicate and '.$process.' records are unique and inserted to system</h4>';  
	
  
  