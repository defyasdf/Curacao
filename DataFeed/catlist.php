<?php

	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct');
	
	$sql = "SELECT * FROM `maincatids`";
	$resu = mysql_query($sql);
	echo '<table>';
	echo '<tr><td>Magento Category </td><td>Parent Category Id</td></tr>';		
	while($row = mysql_fetch_array($resu)){	

	$sql1 = 'select * from categories where magento_category_id = '.$row['magentocat_id'];
	$re1 = mysql_query($sql1);
	$row1 = mysql_fetch_array($re1);

	$sql2 = 'select * from categories where magento_category_id = '.$row['main_parent_id'];
	$re2 = mysql_query($sql2);
	$row2 = mysql_fetch_array($re2);	
		
		echo '<tr><td>'.$row1['name'].'</td><td>'.$row2['name'].'</td></tr>';		
	}
	echo '</table>';