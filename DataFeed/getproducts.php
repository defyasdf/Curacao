<?php
ini_set('max_execution_time', 300);

$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');

$proxy = new SoapClient('http://data.icuracao.com/api/soap/?wsdl');
$sessionId = $proxy->login('snprajapati', 'sanjay123');

$products = $proxy->call($sessionId, 'product.list');
for($i=2;$i<sizeof($products);$i++){

	$sql = "SELECT `product_cost`,`product_retail`,`product_msrp` FROM `finalproductlist` WHERE `product_sku` = '".$products[$i]['sku']."'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	
	$cost = (int)(substr($row['product_cost'],1));
	$price = (int)(substr($row['product_retail'],1));
	$msrp = (int)(substr($row['product_msrp'],1));

	$proxy->call($sessionId, 'product.update', array($products[$i]['sku'], array('price'=>$price,'msrp'=>$msrp,'cost'=>$cost)));
}

	
?>