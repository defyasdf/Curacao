<?php 
	require_once('/var/www/upgrade/custom/_includes/ini_settings.php');
	require_once('/var/www/upgrade/custom/_includes/db_config_mage.php');
	
	$query = "SELECT cp.sku,cp.entity_id,cpv.value FROM `catalog_product_entity` as cp, catalog_product_entity_varchar as cpv WHERE cpv.entity_id=cp.entity_id and cpv.value='Domestic' and cpv.attribute_id='179'";
	$re = mysql_query($query);
	$data = array();
	$data = '';
	$skuqty = array();
	while($row = mysql_fetch_assoc($re)){
		$data[] = $row['sku'].';';
		$skuqty[trim($row['sku'])] = $row['entity_id']; 
		$select = 'select * from icuracaoproduct.curacaosku where product_id = "'.$row['entity_id'].'"';
		$rese = mysql_query($select);
		if(mysql_num_rows($rese) == 0){
			$insquery = 'insert into icuracaoproduct.curacaosku(product_id,sku)value("'.$row['entity_id'].'","'.trim($row['sku']).'")';
			mysql_query($insquery) or die(mysql_error());
		}
	}
	$product_data = array_chunk($data, 4000);
	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	//set the headers values
	$headerbody = array('UserName' => 'mike', 'Password' => 'ecom12');
	//Create Soap Header.
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);
	//set the Headers of Soap Client.
	$h = $proxy->__setSoapHeaders($header);
	$qty = array();
	$j = 1;
	//echo sizeof($product_data);
	foreach($product_data as $pdata){
		$skus = '';
		for($i=0;$i<sizeof($pdata);$i++){
			$skus .= $pdata[$i];
		}
		
		$credit = $proxy->BatchGetInventory(array('SkuList'=>utf8_encode($skus),'location'=>'06'),
											 "http://www.lacuracao.com/LAC-eComm-WebServices", 
											 "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
											 false, null , 'rpc', 'literal');
		$result = $credit->BatchGetInventoryResult;
		$resultJson = json_decode($result);
		$qty[] = $resultJson->SKULIST;
		
	}
	
	
	foreach($qty as $q){
		foreach ($q as $cq){
			
			if(array_key_exists($cq[0],$skuqty)){
				$productmagid = $skuqty[$cq[0]];
			}else{
				$invquery = "SELECT sku,entity_id FROM `catalog_product_entity` where sku like '%".$cq[0]."%'";
				$invre = mysql_query($invquery);
				$invRow = mysql_fetch_array($invre);
				$productmagid = $invRow['entity_id'];	
			}
			if($cq[2]>0){
				$stockstatus = 1;
			}else{
				$stockstatus = 0;
			}
			$thrsku = explode('-',$cq[0]);
			
			$thrquery = 'select * from icuracaoproduct.thrashold16 where dept = "'.trim($thrsku[0]).'"';
			$thrres = mysql_query($thrquery);
			$thrnum = mysql_num_rows($thrres);
			$tot = 0;
			$storeinv = 0;
			$threshold = 0;

			if($thrnum>0){
				$thrrow = mysql_fetch_array($thrres);
				$threshold = $thrrow['thrashold'];
				if($threshold == 'no' || trim($threshold) == ''){
					$tot = 0;
				}else{
					$thr = (int)$threshold;
					$tot = (int)$cq[2] - $thr;
				}
			}else{
				$tot = (int)$cq[2];
			}
			if($tot<0){
				$tot = 0;
			}
						
			$update = "update icuracaoproduct.curacaosku set ar_status = 1,qty = '".$cq[2]."',qtythr = '".$tot."',threshold='".$threshold."' where product_id = '".$productmagid."'";
			mysql_query($update) or die(mysql_error());
			
			$cronattr = "SELECT *  FROM `catalog_product_entity_int` WHERE `attribute_id` = 237 and entity_id = '".$productmagid."'";
			$crnre = mysql_query($cronattr);
			$crnRow = mysql_fetch_array($crnre);
			
			if($crnRow != '499'){
				$database = mysql_query("UPDATE curacao_magento.cataloginventory_stock_item item_stock, curacao_magento.cataloginventory_stock_status status_stock  SET item_stock.qty = '".$tot."', item_stock.is_in_stock = $stockstatus, status_stock.qty = '".$tot."', status_stock.stock_status = $stockstatus WHERE item_stock.product_id = '$productmagid' AND item_stock.product_id = status_stock.product_id ") or die(mysql_error());
				
				if($tot == 0){
					$dq = "UPDATE `curacao_magento`.`catalog_product_entity_int` SET `value` = '2' WHERE `catalog_product_entity_int`.`attribute_id` =96 and `catalog_product_entity_int`.entity_id = '".$productmagid."'";	
					mysql_query($dq) or die(mysql_error());
				}else{
					$dq = "UPDATE `curacao_magento`.`catalog_product_entity_int` SET `value` = '1' WHERE `catalog_product_entity_int`.`attribute_id` =96 and `catalog_product_entity_int`.entity_id = '".$productmagid."'";	
					mysql_query($dq) or die(mysql_error());
				}
			}
			
		}
	}

		/*$sql = 'SELECT * FROM icuracaoproduct.`curacaosku` WHERE `ar_status` =0';
		$rsql1 = mysql_query($sql);
		while($rw = mysql_fetch_array($rsql1)){
			//$database = mysql_query("UPDATE curacao_magento.cataloginventory_stock_item item_stock, curacao_magento.cataloginventory_stock_status status_stock  SET item_stock.qty = '0', item_stock.is_in_stock = $stockstatus, status_stock.qty = '0', status_stock.stock_status = $stockstatus WHERE item_stock.product_id = '$productmagid' AND item_stock.product_id = status_stock.product_id ") or die(mysql_error());
			//$dq = "UPDATE `curacao_magento`.`catalog_product_entity_int` SET `value` = '2' WHERE `catalog_product_entity_int`.`attribute_id` =96 and `catalog_product_entity_int`.entity_id = '".$rw['product_id']."'";	
			//mysql_query($dq) or die(mysql_error());
		}
		*/
echo $cquery = "insert into icuracaoproduct.inventorycronstatus06(cronday, status) values('".date("Y-m-d H:i:s")."','1')";
	mysql_query($cquery) or die(mysql_error());
	
	mysql_close($link);