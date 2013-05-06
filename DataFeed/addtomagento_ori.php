<?php 


$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');

$sql = 'select * from finalproductlist where fpl_id = '.$_GET['pId'];
$result = mysql_query($sql);

$row = mysql_fetch_array($result);



$name = $row['prduct_name'];
$short = $row['product_description'];
$full = $row['product_description'];
$feature = $row['product_features'];
$spacs = $row['product_specs'];
$price = $row['product_retail'];
$sku = $row['product_sku'];
$qty = $row['product_qty'];
$img = $row['product_img_path'];

$client = new SoapClient('http://127.0.0.1/myproject/iCura/magento/api/soap/?wsdl');
 

$session = $client->login('snprajapati', 'sanjay123');


$attributeSets = $client->call($session, 'product_attribute_set.list');
$set = current($attributeSets);

$newProductData = array(
    'name'              => $name,
     // websites - Array of website ids to which you want to assign a new product
    'websites'          => array(1), // array(1,2,3,...)
    'short_description' => $short,
    'description'       => $full,
	'spec001'       => $spacs,
	'feature'       => $feature,
    'status'            => 1,
    'weight'            => 0,
    'tax_class_id'      => 1,
    'categories'    => array(3),    //3 is the category id   
    'price'             => $price
);
 

$newImage = array(
    'file' => array(
        'name' => $name,
        'content' => base64_encode(file_get_contents($img)),
        'mime'    => 'image/jpeg'
    ),
    'label'    => 'Cool Image Through Soap',
    'position' => 1,
    'types'    => array('image','small_image','thumbnail'),
    'exclude'  => 0
); 
 
 

// Create new product
$client->call($session, 'product.create', array('simple', $set['set_id'], $sku, $newProductData));
$client->call($session, 'product_stock.update', array($sku, array('qty'=>$qty, 'is_in_stock'=>1)));


$imageFilename = $client->call($session, 'product_media.create', array($sku, $newImage));
 
// Get info of created product
$p = $client->call($session, 'product.info', $sku);

if(isset($p['product_id'])){
	$sql = "UPDATE `finalproductlist` SET `magento_product_id` = '".$p['product_id']."' WHERE `fpl_id` = ".$_GET['pId'];
	if(mysql_query($sql)){
		echo '<h4 class="alert_success">Product has been added to magento please review the final product in magento and turn it on.</h4>';
	}else{
		echo '<h4 class="alert_warning">Product has been added to magento but record is not updated in the system.</h4>';
	}
}else{

	echo '<h4 class="alert_error">Product has not been added to magento successfully please check magento.</h4>';
}