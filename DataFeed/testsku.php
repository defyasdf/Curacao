<?php

$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');

$sql1 = "SELECT * FROM `magentoque` where mstatus = 1";
$result1 = mysql_query($sql1);

while($row1 = mysql_fetch_array($result1)){

$sql = 'select * from finalproductlist where fpl_id = '.$row1['finalproId'];
$result = mysql_query($sql);

$row = mysql_fetch_array($result);

if((substr($row['product_retail'],0,1)) == '$'){
	$price = (substr($row['product_retail'],1));
}else{
	$price = $row['product_retail'];
}
if((substr($row['product_cost'],0,1)) == '$'){
	$cost = (substr($row['product_cost'],1));
}else{
	$cost = $row['product_cost'];
}
if((substr($row['product_msrp'],0,1)) == '$'){
	$msrp = (substr($row['product_msrp'],1));
}else{
	$msrp = $row['product_msrp'];
}
$sku = $row['product_sku'];


$client = new SoapClient('http://data.icuracao.com/api/soap/?wsdl');
 

$session = $client->login('snprajapati', 'sanjay123');

$p = $client->call($session, 'product.info', $sku);

$pid = $p['product_id'];

$newProductData_update = array(
    'price'=> $price,
	'cost' => $cost,
	'msrp' => $msrp
);


$client->call($session, 'product.update', array($pid, $newProductData_update));

echo $pid.'Updated<br>';


}