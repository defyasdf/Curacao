<?php
	error_reporting(0);
	ini_set('max_execution_time', 300);
	include 'reader.php';
    $excel = new Spreadsheet_Excel_Reader();
?>
 <?php
    $excel->read('curacao_skus.xls');    

    $x=2;
	
	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct',$link) or die('not connecting');
	

//	$conn = mysql_connect('localhost','root','');
//	mysql_select_db('icuracaoproduct',$conn);
 
  for($i=2;$i<=$excel->sheets[0]['numRows'];$i++){
	  //INSERT INTO `icuracaoproduct`.`specials` (`product_id`, `special`, `cost`, `price`, `category_id`, `datefrom`, `dateto`) VALUES ('', '', '', '', '', '', '')
	  	
		$sql = "insert into curacaoskuonmagento(magento_product_id, sku, price) values('".$excel->sheets[0]['cells'][$i][1]."', '".$excel->sheets[0]['cells'][$i][2]."', '')";
					
					
	  
	/*	 $sql = "INSERT INTO `specials` (`product_id`, `special`, `cost`, `price`, `category_id`, `datefrom`, `dateto`) VALUES ('".$excel->sheets[0]['cells'][$i][1]."','".$excel->sheets[0]['cells'][$i][2]."','".$excel->sheets[0]['cells'][$i][3]."','".$excel->sheets[0]['cells'][$i][4]."','".$excel->sheets[0]['cells'][$i][5]."','".$excel->sheets[0]['cells'][$i][6]."','".$excel->sheets[0]['cells'][$i][7]."'";
		  $sql .= ')';
	*/		
		 mysql_query($sql) or die("Record Not Inserted:".mysql_error());
	
				 $process++;
	}
		
 	
	
	$dup = $total - $process;
	if($_GET['type']==1){ 
		echo '<h4 class="alert_success" style="margin:40px 1% 0">File has Processed Successfully: Out of '.$total.' records '.$dup.' records are duplicate and '.$process.' records are unique and inserted to system</h4>';  
	}else{
		echo '<h4 class="alert_success" style="margin:40px 1% 0">File has Processed Successfully: Out of '.$total.' records '.$process.' records been updated</h4>';  
	}
  
  