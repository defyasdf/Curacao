<?php
	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata') or die('not connecting');
	mysql_select_db('icuracaoproduct');
	
	$sql = 'select * from masterproducttable where status != "2" and product_sku = "'.$_POST['psku'].'"';
	$result = mysql_query($sql);
	
	if(mysql_num_rows($result)>0){
		echo '1';
	}else{
		echo '0';
	}