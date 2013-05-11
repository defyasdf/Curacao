<?php
	//print_r($_REQUEST);
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';

	$link = mysql_connect($server,$user,$pass);
	mysql_select_db($db,$link);

	//echo "Billing address is done";
	$sql = 'update checkouttrack set place_order = 1 where checkouttrackid = "'.$_POST['checkouttrackid'].'"';
	mysql_query($sql);

?>