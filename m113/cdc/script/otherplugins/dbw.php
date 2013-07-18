<?php
$connect = mysql_connect("192.168.100.121","curacaodata","curacaodata");
mysql_select_db("cdc",$connect);

function list_users()
{
$query = mysql_query("SELECT * FROM `users` WHERE `access_level`!=1");
$data = "<select id='user' name='user' required>";
while($queryrow = mysql_fetch_object($query))
{
	$data.="<option value='".$queryrow->user_id."'>";
	$data.=$queryrow->fname." ".$queryrow->lname;
	$data.="</option>";
}
$data.="</select>";
return $data;
}

function list_source()
{
$query = mysql_query("SELECT * FROM `vendormanagement` WHERE `status`=1");
$data = "<select id='pSource' name='pSource' required>";
while($queryrow = mysql_fetch_object($query))
{
	$data.="<option value='".$queryrow->vmID."'>";
	$data.=$queryrow->vendorName." ".$queryrow->vendorID;
	$data.="</option>";
}
$data.="</select>";
return $data;
}


?>