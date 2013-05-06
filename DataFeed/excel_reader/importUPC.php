<?php

	error_reporting(0);
	ini_set('max_execution_time', 300);
	include 'reader.php';
    $excel = new Spreadsheet_Excel_Reader();
?>
 <?php
    $excel->read('upcmodels.xls');    
    $x=2;
	
	$conn = mysql_connect('localhost','root','');
	mysql_select_db('upctoproids',$conn);
 
  for($i=2;$i<=$excel->sheets[0]['numRows'];$i++){
	
	 $sql = "INSERT INTO `upcstoeproids2` (`upcs`, `models`,skus) VALUES (";
			for($j=1;$j<=$excel->sheets[0]['numCols'];$j++){
				//echo '<div>'.$excel->sheets[0]['cells'][$i][$j].'</div>';
				
				$cell = trim($excel->sheets[0]['cells'][$i][$j]);
				
				if($j==3){
						$sql .= "'".htmlspecialchars($cell,ENT_QUOTES )."'";	
					}else{
						$sql .= "'".htmlspecialchars($cell,ENT_QUOTES )."',";
				}
			}
			 $sql .= ')';
			 mysql_query($sql) or die("Record Not Inserted");
			 $process++;
		 }
 	
	
	$dup = $total - $process;
	if($_GET['type']==1){ 
		echo '<h4 class="alert_success" style="margin:40px 1% 0">File has Processed Successfully: Out of '.$total.' records '.$dup.' records are duplicate and '.$process.' records are unique and inserted to system</h4>';  
	}else{
		echo '<h4 class="alert_success" style="margin:40px 1% 0">File has Processed Successfully: Out of '.$total.' records '.$process.' records been updated</h4>';  
	}
  
  