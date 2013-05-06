<html dir="ltr"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<?php
$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');

$sql = "SELECT * FROM `finalproductlist` where status = '1'";
$result = mysql_query($sql);
$num = 1;
echo '<div><div style="float:left; width:48%"><h2>English Version</h2></div><div style="float:left; width:48%"><h2>Spanish Version</h2></div></div>';
echo '<hr>';
while($row = mysql_fetch_array($result)){
	echo '<div style="clear:both;">';
	
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
	
	$sql1 = "SELECT * FROM `spenishdata` where sppr_id = ".$row['spenish_id'];
	$result1 = mysql_query($sql1);
	$row1 = mysql_fetch_array($result1);
	
	echo '<div id="productcontainer">';
		echo '<h2>Product :'.$num.' : '.$row1['product_sku'].'</h2>';
		echo '<table>';
			echo '<tr><td valign=top>Product Name</td><td id="proinfo">'.htmlspecialchars_decode($row1['prduct_name'],ENT_QUOTES).'</td></tr>';
			echo '<tr><td valign=top>Product UPC</td><td id="proinfo">'.$row1['product_upc'].'</td></tr>';
			echo '<tr><td valign=top>Product Description</td><td id="proinfo">'.htmlspecialchars_decode($row1['product_description'],ENT_QUOTES).'</td></tr>';
			echo '<tr><td valign=top>Product Features</td><td id="proinfo">'.htmlspecialchars_decode($row1['product_features'],ENT_QUOTES).'</td></tr>';
			echo '<tr><td valign=top>Product Specifications</td><td id="proinfo">'.htmlspecialchars_decode($row1['product_specs'],ENT_QUOTES).'</td></tr>';
		echo '</table>';
	echo '</div>';
		
	
	echo '</div>';	
	echo '<hr>';
	$num++;
}

?>

<style type="text/css">
	#productcontainer{
		width:48%;
		float:left;
	}
	hr{
		clear:both;
	}
</style>