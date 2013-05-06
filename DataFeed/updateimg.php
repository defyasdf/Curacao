<?php

$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');


$sql = "SELECT * FROM `finalproductlist` WHERE `product_img_path` LIKE '%&amp;%'";
$result = mysql_query($sql);
echo  '<table>';
while($row = mysql_fetch_array($result)){
	//echo '<tr><td>'.$row['product_img_path'].'</td><td>'.str_replace('..','.',$row['product_img_path']).'</td></tr>';
	$q = 'update finalproductlist set product_img_path = "'.str_replace('&amp;','&',$row['product_img_path']).'" where fpl_id = '.$row['fpl_id'];
	mysql_query($q);
}
echo "</table>";