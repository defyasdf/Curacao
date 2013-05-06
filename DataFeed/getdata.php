<?php 
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);
	
	$sql = 'SELECT status FROM `masterproducttable` GROUP BY status';
	$result = mysql_query($sql);
	$data = array();
	while($row = mysql_fetch_array($result)){
			$s = 'SELECT count(*) as data FROM `masterproducttable` where status = '.$row['status'];
			$r = mysql_query($s);
			$ro = mysql_fetch_array($r);
			
			$data[$row['status']] = $ro['data'];
	}
	
	echo '<pre>';
		print_r($data);
	echo '</pre>';
	
		$status = array('0'=>'In Queue (English ready from us)',
						'1'=>'Archieve',
						'2'=>'Pending',
						'3'=>'Raw',
						'4'=>'In Process',
						'5'=>'QA',
						'6'=>'Active',
						'7'=>'English ready From vIndia'
						);				
				
	
	
	foreach($data as $d=>$v){
		echo "['".$status[$d]."','".$v."'],<br>";
	}
	
?>