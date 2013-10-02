<?php

$xml = new SimpleXMLElement('<Batch/>');
	$number = $xml->addChild('Number','2446');
	$count = $xml->addChild('Count','1');
	$order1 = $xml->addChild('Order');
		$order1->addChild('pickmsg',"Send morris the information to check the order");
		$order1->addChild('po',"10002546");
		$order1->addChild('count',"1");
			$shipTo = $order1->addChild("ShipTo");
				$address = $shipTo->addChild("Address");
					$address->addChild("Line1","First Last");
					$address->addChild("Street1","1605 W. Olympic Blvd");
					$address->addChild("Street2");
					$address->addChild("City","Los Angeles");
					$address->addChild("State","CA");
					$address->addChild("Zip","90015");
					$address->addChild("Country","US");
					$address->addChild("Phone","213-639-2487");
			$lineItems = $order1->addChild("LineItems");
				$line1 = $lineItems->addChild("Line");
	//				$line1->addChild("sku",'FW120822SM');
					$line1->addChild("part",'MB010');
					$line1->addChild("qty",'1');
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
	file_put_contents('batch_2446.xml', $xml->asXML());
	// Send morris the information to check the order
	
	try{
		$content = file_get_contents("http://morris.morriscostumes.com/cgi-bin/doxml.cgi?userid=curacao&password=reuben&xml_url=http://www.icuracao.com/custom/morris/batch_2446.xml&message=done");
		//echo $content;
		print_r($content);
	}
	catch (Exception $ex) {
		echo $ex;	
	}		
	echo 'Order Processed Successfully';
}
catch (Exception $ex) {
	echo $ex;	
}