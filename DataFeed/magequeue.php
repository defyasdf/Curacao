<?php 
	include 'includes/config.php';
	
	$sql = "select fpl_id from finalproductlist where  `prduct_name` != '' AND STATUS =1 AND product_retail != ''";
	$res = mysql_query($sql);
	while($row = mysql_fetch_array($res)){
		$q = "insert into magentoque (finalproId,mstatus) values('".$row['fpl_id']."','3')";
		mysql_query($q);
	}
?>