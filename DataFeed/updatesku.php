<?php

$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');


$sql = "SELECT * FROM `finalproductlist`";
$result = mysql_query($sql);
echo  '<table>';
while($row = mysql_fetch_array($result)){
	if(ctype_digit($row['product_sku'])){
		echo '<tr><td>'.$row['product_sku'].'</td><td>cur-'.$row['product_source'].'-'.$row['product_sku'].'</td></tr>';
		$q = 'update finalproductlist set product_sku = "cur-'.$row['product_source'].'-'.$row['product_sku'].'" where fpl_id = '.$row['fpl_id'];
		mysql_query($q);
	}
	//
}
echo "</table>";