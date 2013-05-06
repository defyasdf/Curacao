<?php
ini_set('max_execution_time', 300);
$proxy = new SoapClient('http://data.icuracao.com/api/soap/?wsdl');
$sessionId = $proxy->login('curacaoapi', 'curacao');
$allCategories = $proxy->call($sessionId, 'category.tree');

/*echo '<pre>';
	print_r($allCategories);
echo '</pre>';
*/
//exit;


include 'includes/config.php';

$sql = 'select * from categories where parent_id != 0 AND magento_category_id = 0 limit 0,30';
$res = mysql_query($sql);
while($row = mysql_fetch_array($res)){
	//echo $row['name'].'<br>';
	
$sql1 = 'select magento_category_id from categories where id = '.$row['parent_id'];
$res1 = mysql_query($sql1);
$row1 = mysql_fetch_array($res1);
	
	
	$newCategoryId = $proxy->call(
    $sessionId,
    'category.create',
    array(
        $row1['magento_category_id'],
         array(
                'name'=>$row['name'],
                'is_active'=>1,
                'include_in_menu'=>1,
                'available_sort_by'=>'position',
                'default_sort_by'=>'position'
               )
    )
);
	
	$q = 'update categories set magento_category_id = '.$newCategoryId.' where id = '.$row['id'];
	mysql_query($q);
	
	echo 'one category has been updated <br>';
//	exit;	
}
