<?php
	error_reporting(0);
	ini_set('max_execution_time', 300);
	include 'reader.php';
    $excel = new Spreadsheet_Excel_Reader();
?>
 <?php
    $excel->read('fullcatlist.xls');    

    $x=2;
	
	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct');
 $process = 1;
  for($i=2;$i<=$excel->sheets[0]['numRows'];$i++){
		 
	 $sql = 'update  finalproductlist set magento_category_id = "'.$excel->sheets[0]['cells'][$i][2].'" where fpl_id = "'.$excel->sheets[0]['cells'][$i][1].'"';
	 
	 mysql_query($sql)or die(mysql_error());
	 
	 
			 $process++;
		 }
 	
	
	$dup = $total - $process;
	
		echo '<h4 class="alert_success" style="margin:40px 1% 0">File has Processed Successfully: '.$process.' records are updated to system</h4>';  
	
  
  