<?php

	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct');
	
	$sql = 'select * from users where username = "'.$_POST['uname'].'"';
	$result = mysql_query($sql);
	
	if(mysql_num_rows($result)>0){
		echo '1';
	}else{
		echo '0';
	}