<?php

$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct');

echo $sql = 'select * from spenishdata where product_upc = "884392559489"';
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

echo htmlentities($row['prduct_name'],ENT_QUOTES);