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

$query = "SELECT * FROM emailcamp";
$re = mysql_query($query,$link1);

$data = array();

while($row1 = mysql_fetch_array($re)){
	
	$q = "select * from customer_entity where email = '".$row1['email']."'";
	$res = mysql_query($q,$link);
	$r = mysql_fetch_array($res);
	 
	$sql = "SELECT * FROM `sales_flat_quote` WHERE `customer_id` = '".$r['entity_id']."'";
	$result = mysql_query($sql,$link);
	//$row = mysql_fetch_array($result);
	$total = 0;
	$num = mysql_num_rows($result);
	$ordered = 0;
	$total_purchase = 0;
	$order_number = array();
	$status = array();
	$total_order_finished = 0;
	$curacao_payment_total = 0;
	$customer_number = '';
	if($num>0){
		
		while($row = mysql_fetch_array($result)){
			$total += $row['grand_total'];
			if($row['reserved_order_id']){
				$ordered++;
				$total_purchase += $row['grand_total'];
				$order_number[] = $row['reserved_order_id'];
				$s = "select status,curacaocustomerdiscount,curacaocustomernumber from sales_flat_order where increment_id = '".$row['reserved_order_id']."'";
				$rs = mysql_query($s,$link);
				$rws = mysql_fetch_array($rs);
				$status[] = $rws['status'];
				$curacao_payment_total += $rws['curacaocustomerdiscount'];
				$customer_number = $rws['curacaocustomernumber'];
				if($rws['status'] == 'complete'){
					$total_order_finished += $row['grand_total'];
				}
			}
		}
	}
	$on = implode('_',$order_number);
	$os = implode('_',$status);
	
	$data[] = array("Customer_email"=>$row1['email'],"Customer_Account"=>$customer_number,"No_of_order"=>$num,"Total_purchase_add_to_cart"=>$total,"Total_purchase"=>$total_purchase,"Curacao_credit_purchase"=>$curacao_payment_total,"Total_purchase_completed"=>$total_order_finished,"Number_order_completed"=>$ordered, "OrderNumber"=>$on, "Order_Statuses"=>$os);
	
	/*echo '<tr>';
		echo "<td>".$row1['promo']."</td><td>".$row1['accnumber']."</td><td>".$row['grand_total']."</td><td>".$row['curacaocustomerdiscount']."</td><td></td>";
//	echo $row1['promo'].' '.$row1['accnumber'].' '.$row['grand_total'].' '.$row['curacaocustomerdiscount'].' '."<br>";
	echo '</tr>';
*/
}


 $filename = "swepstack_Purcahse_history.xls";
	
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

//echo '</table>';