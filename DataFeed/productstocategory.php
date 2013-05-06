<?php
//ini_set('max_execution_time', 0);

$proxy = new SoapClient('http://data.icuracao.com/api/soap/?wsdl');
$sessionId = $proxy->login('snprajapati', 'sanjay123');

$products = $proxy->call($sessionId, 'product.list');
print_r($products);
exit;
$pId = array();
$main = array();

for($i=2;$i<sizeof($products);$i++){
		$main[] = $products[$i]['sku'];
		$pId[] = $products[$i]['product_id'];		
}

$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');

	for($j=0;$j<sizeof($main);$j++){

		$sql = 'select * from finalproductlist where magento_product_id = "'.$pId[$j].'"';
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);		
		echo $row['magento_category_id'];
		$proxy->call($sessionId, 'category.assignProduct', array($row['magento_category_id'], $main[$j]));
		
		
	}	
	
	

/*$sql = 'select * from finalproductlist where fpl_id = 1';
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$catID = (int)$row['magento_category_id'];
$productId = $row['magento_product_id'];

$proxy->call($sessionId, 'category.assignProduct', array($catID, $productId));
*/
?>
