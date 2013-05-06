<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<?php 
ini_set('max_execution_time', 300);
	
require_once 'src/apiClient.php';
require_once 'src/contrib/apiTranslateService.php';


$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct',$link);

$s = "select * from product_queue where status = 0 limit 0,1";
$rs = mysql_query($s);
$ros = mysql_fetch_array($rs);

		$sql = "INSERT INTO `finalproductlist` (`prduct_name`, `product_description`, `product_sku`, `product_upc`, `product_msrp`, `product_map`,  `product_brand`, `product_img_path`, `product_features`, `product_source`, `product_user`, `product_specs`,`status`) VALUES ('".$ros['prduct_name']."', '".$ros['product_description']."', '".$ros['product_sku']."', '".$ros['product_upc']."',  '".$ros['product_msrp']."', '".$ros['product_map']."', '".$ros['product_brand']."', '".$ros['product_img_path']."', '".$ros['product_features']."', '', '', '".$ros['product_specs']."','0')";

	if(mysql_query($sql)){
		
		
		$q = 'SELECT fpl_id FROM `finalproductlist` ORDER BY fpl_id DESC LIMIT 0 , 1';
		$r = mysql_query($q);
		$ro = mysql_fetch_array($r);
		$desc = '';
		$ftr = '';
		$spc = '';
		if(trim($ros['product_features'])!=''){
			if(substr(trim(htmlspecialchars_decode($ros['product_features']),ENT_QUOTES),0,1)=='<'){
				$ftr =  htmlspecialchars(processhtml(htmlspecialchars_decode($ros['product_features'],ENT_QUOTES)));			
				
			}else{
				$ftr = splitstring(htmlspecialchars($ros['product_features'],ENT_QUOTES));			
			
			}
		}
		if(substr(trim(htmlspecialchars_decode($ros['product_specs']),ENT_QUOTES),0,1)=='<'){
		
			//if(substr(trim($ros['product_specs']),0,1)=='<'){
				$spc =  htmlspecialchars(processhtml(htmlspecialchars_decode($ros['product_specs'],ENT_QUOTES)),ENT_QUOTES);			
				
			
			}else{
				$spc = splitstring(htmlspecialchars($ros['product_specs'],ENT_QUOTES));
			
			//}
		}
		
		if(trim($ros['product_description'])!=''){
			$desc = translateplaintext($ros['product_description']);
		}else{
			$desc = '';
		}
		if(trim($ros['prduct_name'])!=''){
			$name = translateplaintext($ros['prduct_name']);
		}else{
			$name = "";
		}
		echo $desc;

		 $sql1 = "INSERT INTO `spenishdata` (`eng_id`,`prduct_name`, `product_description`, `product_sku`, `product_upc`, `product_msrp`, `product_map`,  `product_brand`, `product_img_path`, `product_features`, `product_source`, `product_user`, `product_specs`,`status`) VALUES ('".$ro['fpl_id']."','".$name."', '".$desc."', '".$ros['product_sku']."', '".$ros['product_upc']."', '".$ros['product_msrp']."', '".$ros['product_map']."', '".$ros['product_brand']."', '".$ros['product_img_path']."', '".$ftr."', '', 'sanjay', '".$spc."','0')";
		
		mysql_query($sql1) or die("not insert into spanish");
		
		$q1 = "select * from spenishdata ORDER BY `sppr_id` DESC limit 0,1";
		$r01 = mysql_query($q1);
		$ro1 = mysql_fetch_array($r01);

		$q2	= "UPDATE `finalproductlist` SET `spenish_id` = '".$ro1['sppr_id']."' WHERE `fpl_id` =".$ro['fpl_id'];
		mysql_query($q2);
		
		$query = 'select mpt_id from masterproducttable where product_sku = "'.$ros['product_sku'].'"';
		$re = mysql_query($query);
		while($r = mysql_fetch_array($re)){
			$q = "UPDATE `masterproducttable` SET `status` = '5' WHERE `mpt_id` =".$r['mpt_id'];
			mysql_query($q);
		}
		
		$q1 = "UPDATE `product_queue` SET `status` = '1' WHERE `pq_id` =".$ros['pq_id'];
		mysql_query($q1);
		
		$msg = '<h4 class="alert_success">A Product Created Successfully</h4>';
	}else{
		$msg = '<h4 class="alert_error">Product is not inserted successfully</h4>';
	}
	
	echo $msg;
	
	
	function translateplaintext($str){
	
		$client = new apiClient();
		$client->setApplicationName('Google Translate PHP Starter Application');
		
		// Visit https://code.google.com/apis/console?api=translate to generate your
		// client id, client secret, and to register your redirect uri.
		$client->setDeveloperKey('AIzaSyAqfTrkUwYqrULHJudzwC5FjE11fT5REUQ');
		$service = new apiTranslateService($client);
			
		$translations = $service->translations->listTranslations($str, 'es');
		//print "<h1>Translations</h1><pre>" . print_r($translations, true) . "</pre>";
		
		
		return $translations['translations'][0]['translatedText'];
	
	}


	function splitstring($str){
		
		$client = new apiClient();
		$client->setApplicationName('Google Translate PHP Starter Application');
		
		// Visit https://code.google.com/apis/console?api=translate to generate your
		// client id, client secret, and to register your redirect uri.
		$client->setDeveloperKey('AIzaSyAqfTrkUwYqrULHJudzwC5FjE11fT5REUQ');
		$service = new apiTranslateService($client);
		$s = preg_split ('/$\R?^/m', $str);

		$v = array();
		for($i=0;$i<sizeof($s);$i++){
			
			if(trim($s[$i])!=''){
				$translations = $service->translations->listTranslations($s[$i], 'es');
			}
			
			$v[] = $translations['translations'][0]['translatedText'];
						
			//$v[] = $this->translate(htmlspecialchars_decode($s[$i]));
		}
		
		$string =  implode('<br>',$v);
		
		return $string;

	}
	
	
	function processhtml($htmlContent){
		
			
		if(str_replace("<","",str_replace(">","",str_replace("/","",substr(trim($htmlContent),0,5))))=='br'){
			return '';
		}
		
		$html_tag = substr(trim($htmlContent),1,strpos($htmlContent,'>'));
		
				
		
		$result = '';
//		if(str_replace(">","",$html_tag)=='table'){
		if(substr($html_tag,0,5)=='table'){
		
			$dom = new DOMDocument;
			$dom->loadHTML( $htmlContent );
			$rows = array();
			foreach( $dom->getElementsByTagName( 'tr' ) as $tr ) {
				$cells = array();
				foreach( $tr->getElementsByTagName( 'td' ) as $td ) {
					$cells[] = $td->nodeValue;
				}
				$rows[] = $cells;
			}
		}
			$result .= '<table>';
			for($i=0;$i<sizeof($rows);$i++){
				$result .= '<tr>';
				
				for($j=0;$j<sizeof($rows[$i]);$j++){
					
					$String = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $rows[$i][$j]);
					
					$result .= '<td>'.translate(str_replace("%","",$String)).'</td>';
					//exit;
				}
					
				$result .= '</tr>';
			}
			
			$result .= '</table>';
			
			return $result;
	}
	
	
	function translate($text){
		
//		echo 'https://www.googleapis.com/language/translate/v2?key=AIzaSyAqfTrkUwYqrULHJudzwC5FjE11fT5REUQ&q='.str_replace(' ','%20',trim ($text)).'&source=en&target=es';
		
		$string = file_get_contents('https://www.googleapis.com/language/translate/v2?key=AIzaSyAqfTrkUwYqrULHJudzwC5FjE11fT5REUQ&q='.str_replace(' ','%20',trim ($text)).'&source=en&target=es');
		
		$json = json_decode($string, true);
		
		return $json['data']['translations'][0]['translatedText'];
	}



