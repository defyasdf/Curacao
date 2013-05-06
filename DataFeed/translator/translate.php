<?php
    require_once('LanguageTranslator.php');
 
    $yourApiKey = 'AIzaSyAqfTrkUwYqrULHJudzwC5FjE11fT5REUQ';
 
	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct');
	
	$sql = "SELECT * FROM `categories`";
	
	$resu = mysql_query($sql);
	echo '<table>';
	echo '<tr><td>English</td><td>Spanish</td></tr>';
	while($row = mysql_fetch_array($resu)){

		$sourceData = $row['name'];
		
		$source = 'es';
	 
		$target = 'en';
	 
		$translator = new LanguageTranslator($yourApiKey);
	 
		$targetData = $translator->translate(trim ($sourceData), $source, $target);
		
		echo '<tr><td>'.$sourceData.'</td><td>'.$targetData.'</td></tr>';
		//file_put_contents($targetData, 'file.txt-' . $target);
	}
	
	echo '</table>';
?>