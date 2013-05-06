<?php 

	include 'includes/config.php';

	$sql = 'select * from categories';
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res)){
						
				$data[] = array("Category Name"=>$row['name'], "Magento Category Id"=>$row['magento_category_id']);
	
			}
		
	  $filename = "categories" . date('Ymd') . ".xls";
	
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