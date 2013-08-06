<?php
//Include Class geodata (This class return all the address information)
include 'class.geodata.inc.php';
//Create a new object
$lookup = new EyeGeoData();
$add = $lookup->query($_REQUEST['zip']);

if(sizeof($add)>1){
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_production';
	$link = mysql_connect($server,$user,$pass);
	mysql_select_db($db,$link);	
	$sql = "SELECT * FROM `directory_country_region` where code = '".$add['Province']."'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	
	echo $add['City'].'|'.$add['Province'].'|'.$row['default_name'].'|'.$row['region_id'];
	
}else{
	echo '0';
}