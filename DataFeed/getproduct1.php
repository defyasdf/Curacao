<?php

$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');

$sql = "SELECT * FROM `product_queue` where status = '0' and priority = '3'";
$result = mysql_query($sql);


header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
echo '<products>';
while($row = mysql_fetch_array($result)){
	echo '<productinfo>';
		echo '<productName>';
			echo str_replace('"','',str_replace('&','',str_replace("<"," ",str_replace(">"," ",htmlspecialchars_decode($row['prduct_name'],ENT_QUOTES)))));
		echo '</productName>';
		echo '<productSKU>';
			echo $row['product_sku'];
		echo '</productSKU>';
		echo '<productUPC>';
			echo $row['product_upc'];
		echo '</productUPC>';
		
		/*echo '<productDescription>';
			echo str_replace("’","'",$row['product_description']);
		echo '</productDescription>';*/
		echo '<productFeatures>';
			echo str_replace("’","'",$row['product_features']);
		echo '</productFeatures>';
		/*echo '<productSpecification>';
			echo str_replace('&','&amp;',str_replace('<br>',"<p></p>",str_replace("x","",htmlspecialchars_decode($row['product_specs'],ENT_QUOTES))));
		echo '</productSpecification>';*/
	echo '</productinfo>';
}
echo '</products>';
?>
