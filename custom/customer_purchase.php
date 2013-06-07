<?php
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	
$server = '192.168.100.121';
$user = 'curacaodata';
$pass = 'curacaodata';
$db = 'curacao_magento';



$link = mysql_connect($server,$user,$pass);
$link1 = mysql_connect($server,$user,$pass,true);

mysql_select_db($db,$link) or die("No DB");	
mysql_select_db('icuracaoproduct',$link1) or die("No DB");	

$query = "select * from customer_entity";
$re = mysql_query($query,$link);
//echo '<table border="1"><tr><th>Customer Email</th><th>No. Of Order</th><th>Total Purchase</th><th>Curacao Credit Purchase</th></tr>';
//echo '<table>';
$data = array();
while($row1 = mysql_fetch_array($re)){
	$sql = "SELECT * FROM `sales_flat_order` WHERE `customer_id` = '".$row1['entity_id']."'";
	$result = mysql_query($sql,$link);
	$total = 0;
	$curacao_credit = 0;
	$num = mysql_num_rows($result);
	
	while($row = mysql_fetch_array($result)){
		$total += $row['grand_total'];
		$curacao_credit += $row['curacaocustomerdiscount'];
	}
	$data[] = array("Customer_email"=>$row1['email'],"No_of_order"=>$num,"Total_purchase"=>$total,"Curacao_credit"=>$curacao_credit);
	//echo '<tr>';
		//echo "<td>".$row1['email']."</td><td>".$num."</td><td>".$total."</td><td>".$curacao_credit."</td>";
//	echo $row1['promo'].' '.$row1['accnumber'].' '.$row['grand_total'].' '.$row['curacaocustomerdiscount'].' '."<br>";
	//echo '</tr>';
}

//echo '</table>';

  $filename = "Purcahse_history.xls";
	
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