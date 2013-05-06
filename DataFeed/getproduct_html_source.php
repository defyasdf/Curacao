<?php

$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');

$sql = "SELECT * FROM `product_queue` where status = '0' limit 981,200";
$result = mysql_query($sql);
$num = 1;
while($row = mysql_fetch_array($result)){

	echo '<div id="productcontainer">';
		echo '<h2>Product :'.$num.' : '.$row['product_sku'].'</h2>';
		echo '<table>';
			echo '<tr><td valign=top>Product Name</td><td id="proinfo">'.htmlspecialchars_decode($row['prduct_name'],ENT_QUOTES).'</td></tr>';
			echo '<tr><td valign=top>Product UPC</td><td id="proinfo">'.$row['product_upc'].'</td></tr>';
			echo '<tr><td valign=top>Product Description</td><td id="proinfo">'.htmlspecialchars_decode($row['product_description'],ENT_QUOTES).'</td></tr>';
			echo '<tr><td valign=top>Product Features</td><td id="proinfo">'.htmlspecialchars_decode($row['product_features'],ENT_QUOTES).'</td></tr>';
			echo '<tr><td valign=top>Product Specifications</td><td id="proinfo">'.htmlspecialchars_decode($row['product_specs'],ENT_QUOTES).'</td></tr>';
		echo '</table>';
	echo '</div>';
	
	$num++;
}

/*header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
echo '<products>';

	echo '<productinfo>';
		echo '<productName>';
			echo $row['prduct_name'];
		echo '</productName>';
		echo '<productSKU>';
			echo $row['product_sku'];
		echo '</productSKU>';
		echo '<productUPC>';
			echo $row['product_upc'];
		echo '</productUPC>';
		
		echo '<productDescription>';
			echo str_replace("’","'",$row['product_description']);
		echo '</productDescription>';
		echo '<productFeatures>';
			echo str_replace("’","'",$row['product_features']);
		echo '</productFeatures>';
		echo '<productSpecification>';
			echo str_replace('&','&amp;',str_replace('<br>',"<p></p>",str_replace("’","'",htmlspecialchars_decode($row['product_specs'],ENT_QUOTES))));
		echo '</productSpecification>';
	echo '</productinfo>';
//}
echo '</products>';
*/?>
