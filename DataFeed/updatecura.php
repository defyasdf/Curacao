<?php

ini_set('max_execution_time', 300);

$server = '192.168.100.121';
$user = 'curacaodata';
$pass = 'curacaodata';
$db = 'icuracaoproduct';


$link = mysql_connect($server,$user,$pass);

mysql_select_db($db,$link);

$sql = "SELECT * FROM `finalproductlist` WHERE `product_cost` = '' and status = 1";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)){

	$all = file_get_contents('http://www.lacuracao.com/ar_stock_ws/testqty.php?sku='.$row['product_sku']);
	
	$info = explode(',',$all);
	
	$sku = explode(':',$info[0]);
	
	$cost = explode(':',$info[1]);
	
	$retail = explode(':',$info[2]);
	
	$qty = explode(':',$info[3]);
	
	echo $sku[0].' : '.$sku[1].' , '.$cost[0]. ' : '. $cost[1].' , '.$retail[0].' : '.$retail[1]; 
	
	if($cost[1]>0){
		$q = "update finalproductlist set product_cost = '$".$cost[1]."', product_retail = '$".$retail[1]."', product_qty = ".$qty[1]." where fpl_id = ".$row['fpl_id'];
		if(mysql_query($q)){
			echo $sku[0].' : '.$sku[1].' , '.$cost[0]. ' : '. $cost[1].' , '.$retail[0].' : '.$retail[1].' , '.$qty[0].' : '.$qty[1].'<br>/n'; 
		}
	}
	
}