<?php

	ini_set('max_execution_time', 300);
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);
	
	$qu = 'insert into lockedpromo (promoCode) values ("'.$_POST['promo'].'")';
	if(mysql_query($qu)){
		echo 'You been locked because of 3 or more attempts';
	}