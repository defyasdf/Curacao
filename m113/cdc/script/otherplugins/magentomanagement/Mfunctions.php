<?php
function check_fpl_id_exist($fpl_id)
{
	$query = "select fpl_id from finalproductlist where fpl_id='$fpl_id'";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0)
	return true;
	else
	return false;
}
function get_english_data($fpl_id)
{
	$query = "select * from finalproductlist where `fpl_id`='$fpl_id'";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0)
	return mysql_fetch_object($result);
	else
	return false;
}
function get_eng_info($sku)
{
$query = "select * from finalproductlist where product_sku='$sku' LIMIT 0,1";
$result = mysql_query($query);
if(mysql_num_rows($result) > 0)
	return mysql_fetch_object($result);
	else
	return FALSE;
}
function get_categories($fplid)
{
$categoryrow = array();
$category_final_row = array();
$query_result = mysql_query("select * from finalproductlist where fpl_id=$fplid LIMIT 1");
$category = mysql_fetch_object($query_result);
$categoryrow = explode('_',$category->product_category);
$numcategories = sizeof($categoryrow);
for($j=0;$j<$numcategories;$j++)
{
$getmegid = get_magento_cat_ids($categoryrow[$j]);
// echo $getmegid->magento_category_id."<br>";exit;
$category_final_row[] = get_catgory_tree($categoryrow[$j]);
}
// print_r($category_final_row)."<br>";exit;
return $category_final_row;
}
function get_catgory_tree($id)
{
static $tree = array();
$data  = mysql_query("SELECT * FROM `categories` WHERE `id`='$id'");
while($datarow = mysql_fetch_object($data))
{
	if($datarow->id != 1)
	{
	$tree[]=$datarow->magento_category_id;
	get_catgory_tree($datarow->parent_id);
	}
}
return array_unique($tree);
}
function get_magento_cat_ids($id)
{
$query = mysql_query("select * from categories where id=$id");
return mysql_fetch_object($query);	
}
function get_img_name($fplid)
{
$imgname = array();
$query_result = mysql_query("select * from product_images where finalproductlist_fpl_id=$fplid");
if(mysql_num_rows($query_result) > 0 )
{
while($imagedatarow = mysql_fetch_object($query_result))
{
if($imagedatarow->fileplacement == 2 )
$imagelocation = IMAGES_LOCATION_CDC_URL."/resize/".$imagedatarow->img_name;
else
$imagelocation = IMAGES_LOCATION_CDC_URL."/images/".$imagedatarow->img_name;
$targeturl = IMAGES_MAGENTO_LOCATION_URL.''.$imagedatarow->img_name;
							// copy image section starts
							if(!@copy($imagelocation,$targeturl))
							{
								$errors= error_get_last();
								echo $errors['message'];
								exit;
							} else {
														
								$imgname[] = $targeturl;
							}
							// copy image section ends
}
return $imgname;
}
else
{
return false;
}
}

function final_stage_done($fpl_id)
{
$data = mysql_query("UPDATE finalproductlist SET status=12 where fpl_id=$fpl_id");
return true;
}

function get_attributes($datas)
{
$retrunarray = array();
$realname = array();
$sizeb = sizeof($datas);
$value2 = explode('_',$datas);
$realname = get_att_save_value($value2[1]);
return $realname;
}
// string ends 
function get_att_save_value($id)
{
	$data = mysql_query("select * from `attribute_types` where id=$id");
	$val = mysql_fetch_object($data);
	return $val->attributename;
}


function check_cat_id_exist($id)
{
	$query = "select * from categories where id='$id'";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0)
	return true;
	else
	return false;
}

function get_category_details($id)
{
	$query = "select * from categories where id='$id'";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0)
	return mysql_fetch_object($result);
	else
	return false;
}
function copy_cat_image($imagename)
{
$imagelocation = PLUGINS_URL.'/cropping/categories/'.$imagename;
$targeturl = IMAGES_MAGENTO_CAT_LOCATION_URL.''.$imagename;
$realwebpath = IMAGES_MAGENTO_CAT_WEB_LOCATION_URL.''.$imagename;
if(!@copy($imagelocation,$targeturl))
							{
								$errors= error_get_last();
								echo $errors['message'];
								exit;
							}
return $imagename;
}



function get_categories_tree($catid)
{
static $tree = array();
$data  = mysql_query("SELECT * FROM `categories` WHERE `id`='$catid'");
while($datarow = mysql_fetch_object($data))
{
	if($datarow->id != 1)
	{
	$datareturn = get_catgory_trees($datarow->parent_id);
	}
}
return array_unique($datareturn);
}
function get_catgory_trees($id)
{
static $tree = array();
$data  = mysql_query("SELECT * FROM `categories` WHERE `id`='$id'");
while($datarow = mysql_fetch_object($data))
{
	$tree[]=$datarow->magento_category_id;
	get_catgory_trees($datarow->parent_id);
}
return $tree;
}
function get_spanish_data($fpl_id)
{
	$query = "select * from spenishdata where `eng_id`='$fpl_id'";
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0)
	return mysql_fetch_object($result);
	else
	return false;
}
function productaddons($value)
{
	$skus = array();
	$data = explode('_',$value);
	$valuedata = array_values(array_filter($data));
	$num = sizeof($valuedata);
	for($i=0;$i<$num;$i++)
	{
	$getsku = mysql_query("SELECT * FROM `finalproductlist` WHERE `fpl_id`='$valuedata[$i]'");
	while($getskurow = mysql_fetch_object($getsku))
	$skus[] = $getskurow->product_sku;
	}
return $skus;
}
?>