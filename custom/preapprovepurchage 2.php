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

$query = "SELECT * FROM `preapproved` WHERE (`approve` = 1 or pendingToApprove = 1) and `accnumber` != '' AND `accnumber` != 0";
$re = mysql_query($query,$link1);
echo '<table border="1"><tr><th>Promo</th><th>Acc_number</th><th>Grand Total</th><th>Curacao Credit</th><th>Downpayment</th></tr>';
//echo '<table>';
while($row1 = mysql_fetch_array($re)){
	$sql = "SELECT * FROM `sales_flat_order` WHERE `curacaocustomernumber` LIKE '".$row1['accnumber']."'";
	$result = mysql_query($sql,$link);
	$row = mysql_fetch_array($result);
	echo '<tr>';
		echo "<td>".$row1['promo']."</td><td>".$row1['accnumber']."</td><td>".$row['grand_total']."</td><td>".$row['curacaocustomerdiscount']."</td><td></td>";
//	echo $row1['promo'].' '.$row1['accnumber'].' '.$row['grand_total'].' '.$row['curacaocustomerdiscount'].' '."<br>";
	echo '</tr>';
}

echo '</table>';