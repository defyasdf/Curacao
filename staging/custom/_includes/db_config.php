<?php

	//DB settings
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	$link = mysql_connect($server,$user,$pass);
	mysql_select_db($db,$link);	
