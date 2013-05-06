<?php ini_set('max_execution_time', 300);?>
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
	$conn = mysql_connect('localhost','root','');
	mysql_select_db('jetsuit',$conn);
	?>
	Sheet 1:<br/><br/>
    <table>
    <?php
    /*$excel->read('jet1.xls');    
    $x=2;
    while($x<=$excel->sheets[0]['numRows']) {
      echo "\t<tr>\n";
      $y=1;
	  $sql = "INSERT INTO `jetAirports` (`FAAID`, `ICAOID`, `City`, `State`, `latitude`, `Longitude`, `status`) VALUES (";
      while($y<=$excel->sheets[0]['numCols']) {
        $cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
        //echo "\t\t<td>$cell</td>\n";  
		if($y==7){
			$sql .= "'".$cell."'";	
		}else{
			$sql .= "'".$cell."',";
		}
		//$sql .= "'".$cell."',";
		
        $y++;
      }  
	  $sql .= ')'; 
	  
	  //mysql_query($sql);
     echo "\t\t<td>$sql</td>\n";  
	  echo "\t</tr>\n";
	  exit;*/
     // $x++;
    //}
    ?>    
    </table><br/>
	
  </body>
</html>
