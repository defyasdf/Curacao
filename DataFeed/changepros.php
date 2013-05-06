<?php

$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');


$sql = 'select * from spenishdata1';
$result = mysql_query($sql);
$n = 1;
while($row = mysql_fetch_array($result)){
		
	$final = 'select * from product_queue where product_upc = "'.$row['product_upc'].'" and status = 0';
	$fina = mysql_query($final);
	$fr = mysql_fetch_array($fina);
	if(trim($fr['product_sku'])!=''){
		//echo $n.' '.$fr['product_sku'].'<br>';
		$q = 'update finalproductlist set prduct_name = "'.$fr['prduct_name'].'", product_description = "'.$fr['product_description'].'", product_features = "'.$fr['product_features'].'", product_specs = "'.$fr['product_specs'].'" where product_upc = "'.$row['product_upc'].'"';
		
		mysql_query($q) or die(mysql_error);
		$n++;
	}
	
}
