<html>
  <head>
    <style type="text/css">
    table {
    	border-collapse: collapse;
    }        
    td {
    	border: 1px solid black;
    	padding: 0 0.5em;
    }        
    </style>
  </head>
  <body>
	<?php
	include 'reader.php';
    $excel = new Spreadsheet_Excel_Reader();
	?>
	Sheet 1:<br/><br/>
    <table>
    <?php
    $excel->read('pro.xls');    
    $x=2;
  
//  echo $excel->sheets[0]['cells'][2][2];
  echo $excel->sheets[0]['numRows'];
  exit;
    while($x<=$excel->sheets[0]['numRows']) {
      echo "\t<tr>\n";
      $y=1;
	  
	  $sql = "INSERT INTO `shippedsales` (`ItemOrderDate`, `ItemShipDate`, `OrderID`, `Model`, `ProductModel`, `ItemCondition`, `MissingItems`,`Qty`,`Price`,`ShippingCost`) VALUES (";
      while($y<=$excel->sheets[0]['numCols']) {
        if($y==1||$y==2){
			$d = explode(' ',$excel->sheets[0]['cells'][$x][$y]);
			$da = explode('/',$d[0]);
			//print_r($d);
			$cell = $da[2].'-'.($da[1]).'-'.$da[0];
			//$cell = date('Y-m-d', strtotime($excel->sheets[0]['cells'][$x][$y])) ;
		}else{
			$cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
			
		}
        //echo "\t\t<td>$cell</td>\n";  
		if($cell != ''){
			if($y==10){
				$sql .= "'".$cell."'";	
			}else{
				$sql .= "'".$cell."',";
			}
		}
		//$sql .= "'".$cell."',";
		
        $y++;
      }  
	  $sql .= ')'; 
	  
//	  mysql_query($sql);
     echo "\t\t<td>$sql</td>\n";  
	  echo "\t</tr>\n";
	  //exit;
      $x++;
    }
    ?>    
    </table><br/>
	
  </body>
</html>
