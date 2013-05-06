<?php

	error_reporting(0);
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
	
	$sql = 'update finalproductlist set product_cost = "'.$excel->sheets[0]['cells'][$i][9].'",product_retail= "'.$excel->sheets[0]['cells'][$i][10].'",product_msrp = "'.$excel->sheets[0]['cells'][$i][8].'", product_qty = "'.$excel->sheets[0]['cells'][$i][11].'", magento_category_id = "'.$excel->sheets[0]['cells'][$i][12].'" where fpl_id = "'.$excel->sheets[0]['cells'][$i][1].'"';

	mysql_query($sql) or die('Product has not been updated'.mysql_error());
	
	
		 $sql = 'update spenishdata set product_cost = "'.$excel->sheets[0]['cells'][$i][9].'",product_retail= "'.$excel->sheets[0]['cells'][$i][10].'",product_msrp = "'.$excel->sheets[0]['cells'][$i][8].'", product_qty = "'.$excel->sheets[0]['cells'][$i][11].'" where sppr_id = "'.$excel->sheets[0]['cells'][$i][2].'"';

	mysql_query($sql) or die('Product has not been updated');
	
	$process++;
 }
 	
	$total = $excel->sheets[0]['numRows']-1;
	
	$dup = $total - $process;
	if($_GET['type']==1){ 
		echo '<h4 class="alert_success" style="margin:40px 1% 0">File has Processed Successfully: Out of '.$total.' records '.$dup.' records are duplicate and '.$process.' records are unique and inserted to system</h4>';  
	}else{
		echo '<h4 class="alert_success" style="margin:40px 1% 0">File has Processed Successfully: Out of '.$total.' records '.$process.' records been updated</h4>';  
	}
  
  