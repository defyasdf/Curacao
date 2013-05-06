<?php

$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');

$sql = 'select * from finalproductlist where fpl_id = '.$_GET['pId'];
$result = mysql_query($sql);

$row = mysql_fetch_array($result);

if($row['magento_product_id']>0){

	$client = new SoapClient('http://127.0.0.1/myproject/iCura/magento/api/soap/?wsdl');
	 
	
	$session = $client->login('snprajapati', 'sanjay123');
	
	
	// Delete product
	$client->call($session, 'product.delete', $_GET['sku']);
	 
	try {
		// Ensure that product deleted
		var_dump($client->call($session, 'product.info', 'sku_of_product'));
	} catch (SoapFault $e) {
		
		echo '<h4 class="alert_success">Product has been deleted successfully from Magento.</h4>';
	
	}

}
$sql = "delete from `finalproductlist` WHERE `fpl_id` = ".$_GET['pId'];
mysql_query($sql) or die('Product not deleted');

$sql = 'select * from masterproducttable where product_sku = "'.$_GET['sku'].'"';
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)){
	
	$sql = "UPDATE `masterproducttable` SET `status` = '0' WHERE `mpt_id` = ".$row['mpt_id'];
	mysql_query($sql);
}
echo '<h4 class="alert_success">Product has been unapproved successfully .</h4>';
