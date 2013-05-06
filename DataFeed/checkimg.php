<?php

$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');


$sql = 'select * from finalproductlist where fpl_id = 1';

$result = mysql_query($sql);

$row = mysql_fetch_array($result);

$img = explode(',',$row['product_img_path']);
print_r($img);
for($i=0;$i<sizeof($img);$i++){
	echo $img[$i].'<br>';
	if(file_get_contents($img[$i])){
	//	echo $img[$i].'<br>';
	echo 'hello<br>';	
	}
}
