<?php

	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct');
	$sql = "SELECT * FROM `finalproductlist` WHERE `magento_category_id` !=0 AND `magento_product_id` !=0 AND `inmagento` = 1";
	$resu = mysql_query($sql);
	while($row = mysql_fetch_array($resu)){	
//		echo $row['magento_product_id'];
		echo getmaincat($row['magento_category_id'],$row['magento_product_id']);
		//exit;
	}
	
function getmaincat($catid,$proId){
//	echo $catid;
	$sql = 'select * from categories where magento_category_id = '.$catid;
	$re = mysql_query($sql);
	$row = mysql_fetch_array($re);
	$cat_id = '';
	if($row['parent_id']!=0){

		$sql1 = 'select * from categories where id = '.$row['parent_id'];
		$re1 = mysql_query($sql1);
		$row1 = mysql_fetch_array($re1);	
		getmaincat($row1['magento_category_id'],$proId);
	}else{
		$sql2 = "SELECT * FROM `finalproductlist` WHERE `magento_product_id` = ".$proId." AND `inmagento` = 1";
		$resu2 = mysql_query($sql2);
		$ro = mysql_fetch_array($resu2);
		$q = 'insert into maincatids (magentocat_id,magento_pro_id,main_parent_id) values ('.$ro['magento_category_id'].','.$ro['magento_product_id'].','.$row['magento_category_id'].')';
		mysql_query($q);
		
		$cat_id = $row['magento_category_id'];
	}
//	$cat_id;
	return $cat_id;
}		
/*
function hasParent($parent_id)
{
	$sql = "SELECT COUNT(*) as count FROM categories WHERE parent_id = ' " . $parent_id . " ' ";
	$qry = mysql_query($sql);
	$rs = mysql_fetch_array($qry);
	return $rs['count'];
}
*/
