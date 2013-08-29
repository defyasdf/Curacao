<?php 
	// INI setting
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	// Server DB setting
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_magento';
	$link = mysql_connect($server,$user,$pass);
	$link1 = mysql_connect($server,$user,$pass,true);
	mysql_select_db($db,$link) or die("No DB");	
	mysql_select_db('icuracaoproduct',$link1) or die("No DB");
	// End Server DB settings
	
	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	// Set headers
	$headerbody = array('UserName' => 'mike', 
						'Password' => 'ecom12'); 
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
			
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
	
	$sql = "SELECT * FROM `catalog_product_entity_varchar` WHERE `attribute_id` =183 AND `value` = '2139'";
	$result = mysql_query($sql,$link) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		//Get SKU
		$sql_sku = 'SELECT * FROM `catalog_product_entity` WHERE `entity_id`='.$row['entity_id'];
		$result_sku = mysql_query($sql_sku,$link);
		$row_sku = mysql_fetch_array($result_sku);
		
		//Get SKU
		$sql_dbsku = 'SELECT * FROM `product_price` WHERE `sku`="'.$row_sku['sku'].'"';
		$result_dbsku = mysql_query($sql_dbsku,$link1) or die(mysql_error());
		//$row_sku = mysql_fetch_array($result_sku);
		if(mysql_num_rows($result_dbsku) == 0){
			//Price
			$sql_price = 'SELECT * FROM `catalog_product_entity_decimal` WHERE `attribute_id` =75 AND `entity_id`='.$row['entity_id'];
			$result_price = mysql_query($sql_price,$link);
			$row_price = mysql_fetch_array($result_price);
			//Special
			$sql_spprice = 'SELECT * FROM `catalog_product_entity_decimal` WHERE `attribute_id` =76 AND `entity_id`='.$row['entity_id'];
			$result_spprice = mysql_query($sql_spprice,$link);
			$row_spprice = mysql_fetch_array($result_spprice);
			//Cost
			$sql_cost = 'SELECT * FROM `catalog_product_entity_decimal` WHERE `attribute_id` =79 AND `entity_id`='.$row['entity_id'];
			$result_cost = mysql_query($sql_cost,$link);
			$row_cost = mysql_fetch_array($result_cost);
			//MSRP
			$sql_msrp = 'SELECT * FROM `catalog_product_entity_decimal` WHERE `attribute_id` =120 AND `entity_id`='.$row['entity_id'];
			$result_msrp = mysql_query($sql_msrp,$link);
			$row_msrp = mysql_fetch_array($result_msrp);
			
			//Get run Cron or not
			$sql_msrp = 'SELECT * FROM `catalog_product_entity_int` WHERE `attribute_id` =234 AND `entity_id`='.$row['entity_id'];
			$result_msrp = mysql_query($sql_msrp,$link);
			$row_msrp = mysql_fetch_array($result_msrp);
			
			$credit = $proxy->GetSkuPrice(array('Sku'=>$products->getSku()));
			$result = $credit->GetSkuPriceResult;
			$price_info = explode('|',$result);
			
			$special = $row_spprice['value'];
			$rebate = 0;
			$price = $row_price['value'];
			$cost = $row_cost['value'];
			$msrp = $row_msrp['value'];
			$recycle = 0;
			$recycleDesc = '';			
			
			if($price_info[0]=='OK'){
				
					
			}
			
			
			$insert = "insert into product_price(sku,price,special_price,cost,msrp,updated_date) values ('".$row_sku['sku']."','".$row_price['value']."','".$row_spprice['value']."','".$row_cost['value']."','".$row_msrp['value']."','".date('Y-m-d')."')";
			if(mysql_query($insert,$link1)){
				echo 'Data For '.$row_sku['sku'].' been updated <br>';
			}else{
				echo 'Data For '.$row_sku['sku'].' been not updated <br>';
			}
		}else{
			$credit = $proxy->GetSkuPrice(array('Sku'=>$products->getSku()));
			$result = $credit->GetSkuPriceResult;
			$price_info = explode('|',$result);
			if($price_info[0]=='OK'){
				
			}
		}
		
		
	}