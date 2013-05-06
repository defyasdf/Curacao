<?php 

ini_set('max_execution_time', 0);
$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');

$sql1 = "SELECT * FROM `magentoque` where mstatus = 0";
$result1 = mysql_query($sql1);

while($row1 = mysql_fetch_array($result1)){

$sql = 'select * from finalproductlist where fpl_id = '.$row1['finalproId'];
$result = mysql_query($sql);

$row = mysql_fetch_array($result);

$essql = 'select * from spenishdata where sppr_id = '.$row['spenish_id'];
$esresult = mysql_query($essql);

$esrow = mysql_fetch_array($esresult);


//$categoryId = $row['magento_category_id'];

$list = get_html_translation_table(HTML_ENTITIES);

unset($list['"']);
unset($list['<']);
unset($list['>']);
unset($list['&']);

$search = array_keys($list);
$values = array_values($list);



$name = $row['prduct_name'];
$short = $row['product_description'];
$full = $row['product_description'];
$feature = str_replace($search, $values, $row['product_features']);
$spacs = str_replace($search, $values, $row['product_specs']);


//$str_out = str_replace($search, $values, $str_in);

$esname = str_replace($search, $values, $esrow['prduct_name']);
$esshort = str_replace($search, $values, $esrow['product_description']);
$esfull = str_replace($search, $values, $esrow['product_description']);
$esfeature = str_replace($search, $values, $esrow['product_features']);
$esspacs = str_replace($search, $values, $esrow['product_specs']);


/*$esname = htmlentities($esrow['prduct_name'].ENT_QUOTES);
$esshort = htmlentities($esrow['product_description'],ENT_QUOTES);
$esfull = htmlentities($esrow['product_description'],ENT_QUOTES);
$esfeature = $esrow['product_features'];
$esspacs = $esrow['product_specs'];*/

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
//$qty = $row['product_qty'];
$qty = 10;
$img = explode(',',$row['product_img_path']);


$client = new SoapClient('http://data.icuracao.com/api/soap/?wsdl');
 

$session = $client->login('snprajapati', 'sanjay123');


$attributeSets = $client->call($session, 'product_attribute_set.list');
$set = current($attributeSets);

$newProductData = array(
    'name'              => $name,
    'websites'          => array(1,2), // array(1,2,3,...)
    'short_description' => htmlspecialchars_decode($short,ENT_QUOTES),
    'description'       => htmlspecialchars_decode($full,ENT_QUOTES),
	'spec001'       => htmlspecialchars_decode($spacs,ENT_QUOTES),
	'feature'       => htmlspecialchars_decode($feature,ENT_QUOTES),
    'status'            => 1,
    'weight'            => 0,
    'tax_class_id'      => 1,
    'categories'    => array(3),    //3 is the category id   
    'price'             => $price,
	'cost' => $cost,
	'msrp' => $msrp,
	'tax_class_id' => '2',
	'inventory_manage_stock' => '1'
);


 
$client->call($session, 'product.create', array('simple', $set['set_id'], $sku, $newProductData));
// Get info of created product
$p = $client->call($session, 'product.info', $sku);

$pid = $p['product_id'];

$client->call($session, 'product_stock.update', array($pid, array('qty'=>$qty, 'is_in_stock'=>1)));

$newProductData_update = array(
    'name'              => $esname,
    'short_description' => $esshort,
    'description'       => $esfull,
	'spec001'       => htmlspecialchars_decode($esspacs,ENT_QUOTES),
	'feature'       => htmlspecialchars_decode($esfeature,ENT_QUOTES)
);


$client->call($session, 'product.update', array($pid, $newProductData_update,'espn'));

for($i=0;$i<sizeof($img);$i++){
	if(trim($img[$i]) != ''){
		if(file_get_contents($img[$i])){
			$newImage = array(
				'file' => array(
					'name' => "product_".str_replace('/','',$sku)."_".$i,
					'content' => base64_encode(file_get_contents($img[$i])),
					'mime'    => 'image/jpeg'
				),
				'label'    => $name,
				'position' => 1,
				'types'    => array('image','small_image','thumbnail'),
				'exclude'  => 0
			); 
				
			
			$imageFilename = $client->call($session, 'product_media.create', array($sku, $newImage));
		
		}
	}
}



if(isset($p['product_id'])){
	$sql = "UPDATE `finalproductlist` SET `magento_product_id` = '".$p['product_id']."' WHERE `fpl_id` = ".$row1['finalproId'];
	if(mysql_query($sql)){
		$sql = "UPDATE `magentoque` SET `mstatus` = '1' WHERE `qId` = ".$row1['qId'];
		mysql_query($sql);
		
		echo '<h4 class="alert_success">'.$p['product_id'].' : Product has been added to magento please review the final product in magento and turn it on.</h4>';
	}else{
		echo '<h4 class="alert_warning">Product has been added to magento but record is not updated in the system.</h4>';
	}
}else{

	echo '<h4 class="alert_error">'.$pid.' : Product has not been added to magento successfully please check magento.</h4>';
}

}


