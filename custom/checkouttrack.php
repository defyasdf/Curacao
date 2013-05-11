<?php
	//print_r($_REQUEST);
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';

	$link = mysql_connect($server,$user,$pass);
	mysql_select_db($db,$link);

	$shipping_add = 0;
	$billing_add = 0;
	$payment = 0;
	$shipping_method = 0;
	if(($_REQUEST['billing']['firstname'])&&($_REQUEST['billing']['lastname'])&&($_REQUEST['billing']['telephone'])&&($_REQUEST['billing']['street'][0])&&($_REQUEST['billing']['country_id'])&&($_REQUEST['billing']['city'])&&($_REQUEST['billing']['region_id'])&&($_REQUEST['billing']['postcode'])){

		$billing_add = 1;
		
	}
	
	if($_REQUEST['billing']['use_for_shipping']){
		$shipping_add = 1;
	}elseif(($_REQUEST['shipping']['firstname'])&&($_REQUEST['shipping']['lastname'])&&($_REQUEST['shipping']['telephone'])&&($_REQUEST['shipping']['street'][0])&&($_REQUEST['shipping']['country_id'])&&($_REQUEST['shipping']['city'])&&($_REQUEST['shipping']['region_id'])&&($_REQUEST['shipping']['postcode'])){
		$shipping_add = 1;
	}
	
	if($billing_add){
		//echo "Billing address is done";
		$sql = 'update checkouttrack set billing_add_complete = 1 where checkouttrackid = "'.$_POST['checkouttrackid'].'"';
		mysql_query($sql);
	}
	if($_REQUEST['billing_address_id']){
		$sql = 'update checkouttrack set billing_add_complete = 1 where checkouttrackid = "'.$_POST['checkouttrackid'].'"';
		mysql_query($sql);
	}
	if($shipping_add){
		//echo "shipping address is done";
		$sql = 'update checkouttrack set shipping_add_complete = 1 where checkouttrackid = "'.$_POST['checkouttrackid'].'"';
		mysql_query($sql);
	}
	if($_REQUEST['shipping_address_id']){
		$sql = 'update checkouttrack set shipping_add_complete = 1 where checkouttrackid = "'.$_POST['checkouttrackid'].'"';
		mysql_query($sql);
	}
	if($_REQUEST['payment']['method']){
		$payment = 1;
	}
	if($payment){
		//echo 'Payment clicked';
		$sql = 'update checkouttrack set payment_clicked = 1 where checkouttrackid = "'.$_POST['checkouttrackid'].'"';
		mysql_query($sql);	
	}
	if($_REQUEST['shipping_method']){
		$shipping_method = 1;
	}
	
	if($shipping_method){
		//echo 'Shipping method set';<br />
		$sql = 'update checkouttrack set shipping_click = 1 where checkouttrackid = "'.$_POST['checkouttrackid'].'"';
		mysql_query($sql);
	}
?>