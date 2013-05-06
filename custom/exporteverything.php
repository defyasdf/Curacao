<?php

	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);

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
		
			$sql = "select * from masterproducttable where status != 1 and status != 5 and status != 6";
			$resu = mysql_query($sql);			
			while($row = mysql_fetch_array($resu)){
				$str = cleandata($row['product_description']);
				$features = cleandata($row['product_features']);
				$specs = cleandata($row['product_specs']);			
				
				if($row['product_source'] == 'etilize'){
					$source = 'etilize';
				}else{
					$s = "SELECT * FROM `vendormanagement` where vendorID = '".$row['product_source']."'";
					$r = mysql_query($s);
					$ro= mysql_fetch_array($r);
					$source = $ro['vendorName'];
				}
							
				$data[] = array("name"=>$row['prduct_name'], "sku"=>$row['product_sku'],  "UPC"=>$row['product_upc'],  "Brand"=>$row['product_brand'],"source"=>$source);
	
			}
	
		
		
	  $filename = "product_data_" . date('Ymd') . ".xls";
	
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