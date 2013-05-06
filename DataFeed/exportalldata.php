<?php

	ini_set('max_execution_time', 300);
	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct');
	
	$status = array(			
					'0'=>'In Queue',
					'1'=>'Archieve',
					'2'=>'Pending',
					'3'=>'Raw',
					'4'=>'In Process',
					'5'=>'QA',
					'6'=>'Active'
					);
	
	$sql = "select * from masterproducttable where status = 1 or status = 2 or status = 3 or status = 4 or status = 5 or status = 6 group by product_sku";
	$result = mysql_query($sql);
			
	while($row = mysql_fetch_array($result)){
			
				$str = cleandata($row['product_description']);
				$features = cleandata($row['product_features']);
				$specs = cleandata($row['product_specs']);			
						
				$data[] = array("sku"=>$row['product_sku'],  "UPC"=>$row['product_upc'],"status" => $status[$row['status']]);
	
			}

		
		
		
		
	  $filename = "All_product_data_" . date('Ymd') . ".xls";
	
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
	
	
	function cleandata($string){
		$s = explode(' ',trim(str_replace('<br>','',str_replace('\r\n','',$string))));
			for($i=0;$i<sizeof($s);$i++){
				if(trim($s[$i])==''){
					unset($s[$i]);
				}
			}
			$str = '';
			foreach($s as $v){
				if(trim($v)!=''){
					$str .= trim( preg_replace( '/\s+/', ' ', $v)).' ';
				}
			}
			
			return $str;
	}
	
?>