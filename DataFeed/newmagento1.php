<?php
	ini_set('max_execution_time', 0);
	include 'includes/config.php';

	$proxy = new SoapClient('http://data.icuracao.com/api/soap/?wsdl');
	$sessionId = $proxy->login('curacaoapi', 'curacao');

	
	$sql = 'select * from categories';
	$rew = mysql_query($sql);
	while($row = mysql_fetch_array($rew)){
	
	//$newData = array('is_active'=>0);
	$CategoryId = $row['magento_category_id'];
	$data = array(
    'name' => htmlentities($row['spanish_name'],ENT_QUOTES),
    'is_active' => 1,
    'custom_design' => null,
    'custom_apply_to_products' => null,
    'custom_design_from' => null,
    'custom_design_to' => null,
    'custom_layout_update' => null,
    'description' => htmlentities($row['spanish_name'],ENT_QUOTES),
);
	
//$result = $proxy->catalog_category.update($session, 459, '');


$result = $proxy->call($sessionId, 'category.update', array($row['magento_category_id'], $data,'espn'));


var_dump ($result);
	
}
//	exit;
	/*
	
	$newCategoryId = $proxy->call(
    $sessionId,
    'category.create',
    array(
        $CategoryId,
         array(
                'name'=>'Newopenerp',
                'is_active'=>1,
                'include_in_menu'=>1,
                'available_sort_by'=>'position',
                'default_sort_by'=>'position'
               )
    )
);
 
	echo $newCategoryId;*/

//$newData = array('is_active'=>0);
// update created category on German store view
//$proxy->call($sessionId, 'category.update', array($newCategoryId, $newData));

//	$proxy->call($sessionId, 'category.delete', 14);	