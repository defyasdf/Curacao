<?php
	// INI setting
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	//server DB connection
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_magento';
	$link = mysql_connect($server,$user,$pass);
	mysql_select_db($db,$link);	
	
	$mageFilename = '/var/www/upgrade/app/Mage.php';
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	umask(0);
	Mage::app('default'); 
	//Getting current store ID	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
	$sql = "SELECT * FROM `sales_flat_order` WHERE (status = 'processing' or status = 'internalfulfillment') and senttomc = 0 and hasmc = 1";	
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)){
	$query = "SELECT * FROM `sales_flat_order_item` WHERE `order_id` = ".$row['entity_id'];
	$itemRes = mysql_query($query);
	$num = mysql_num_rows($itemRes);	
	$i = 0;
	if($num>0){	
		$products = array();
		while($itemRow = mysql_fetch_array($itemRes)){
			$product = Mage::getModel('catalog/product');
			$productId = $product->getIdBySku($itemRow['sku']);
			$product->load($productId);
			if($product->getvendorid() == '55676'){
				$i++;
				$products[$itemRow['sku']] = $itemRow['qty_ordered'];
			}
		}
	}
	echo $i;
	$addSql = "SELECT * FROM `sales_flat_order_address` WHERE `parent_id` = ".$row['entity_id']." and address_type = 'shipping'";
	$addresult = mysql_query($addSql);
	$addrow = mysql_fetch_array($addresult);
	// XML creation
		$order1 = new SimpleXMLElement('<Order/>');
		//$number = $xml->addChild('Number','2446');
		//$count = $xml->addChild('Count','1');
		//$order1 = $xml->addChild('Order');
			$order1->addChild('pickmsg',"Send morris the information to check the order");
			$order1->addChild('po',$row['increment_id']);
			$order1->addChild('via',"420");
			$order1->addChild('count',$i);
				$shipTo = $order1->addChild("ShipTo");
					$address = $shipTo->addChild("Address");
						$address->addChild("Line1",$addrow['firstname']." ".$addrow['lastname']);
						$address->addChild("Street1",$addrow['street']);
						$address->addChild("Street2");
						$address->addChild("City",$addrow['city']);
						$address->addChild("State",$addrow['region']);
						$address->addChild("Zip",$addrow['postcode']);
						$address->addChild("Country",$addrow['country_id']);
						$address->addChild("Phone",$addrow['telephone']);
				//$order1->addChild($lineItems);
				$lineItems = $order1->addChild("LineItems");
				if(sizeof($products)>0){
					foreach($products as $k=>$v){
						$line1 = $lineItems->addChild("Line");
						$line1->addChild("part",$k);
						$line1->addChild("qty",$v);
					}
				}
				/*if($num>0){	
					while($itemRow = mysql_fetch_array($itemRes)){
						$product = Mage::getModel('catalog/product');
						$productId = $product->getIdBySku($itemRow['sku']);
						$product->load($productId);
						if($product->getvendorid() == '55676'){
							//$i++;
							$line1 = $lineItems->addChild("Line");
							$line1->addChild("part",$itemRow['sku']);
							$line1->addChild("qty",'1');
						}
					}
				}
					/*$line2 = $lineItems->addChild("Line");
						$line2->addChild("sku",'FW120822SM');
						$line2->addChild("part",'MB010');
						$line2->addChild("qty",'1');*/
		/*$order2 = $xml->addChild('Order');
			$order2->addChild('pickmsg',"This is Pick Massege");
			$order2->addChild('po',"10002546");
			$order2->addChild('count',"2");
				$shipTo = $order2->addChild("ShipTo");
					$address = $shipTo->addChild("Address");
						$address->addChild("Line1","First Last");
						$address->addChild("Street1","1605 W. Olympic Blvd");
						$address->addChild("Street2");
						$address->addChild("City","Los Angeles");
						$address->addChild("State","CA");
						$address->addChild("Zip","90015");
						$address->addChild("Country","US");
						$address->addChild("Phone","213-639-2487");
				$lineItems = $order2->addChild("LineItems");
					$line1 = $lineItems->addChild("Line");
						$line1->addChild("sku",'FW120822SM');
						$line1->addChild("part",'FW120822SM');
						$line1->addChild("qty",'1');
					$line2 = $lineItems->addChild("Line");
						$line2->addChild("sku",'FW120822SM');
						$line2->addChild("part",'FW120822SM');
						$line2->addChild("qty",'1');*/				
							
			//Header('Content-type: text/xml');
			//print($xml->asXML());
			try{
				file_put_contents('po_'.$row['increment_id'].'.xml', $order1->asXML());
				// Send morris the information to check the order
				
				try{
					//$content = file_get_contents("http://morris.morriscostumes.com/cgi-bin/doxml.cgi?userid=curacao&password=reuben&xml_url=http://www.icuracao.com/custom/morris/batch_2446.xml&message=done");
					//echo $content;
					//print_r($content);
				}
				catch (Exception $ex) {
					echo $ex;	
				}		
				echo 'Order Processed Successfully';
			}
			catch (Exception $ex) {
				echo $ex;	
			}
		
		}