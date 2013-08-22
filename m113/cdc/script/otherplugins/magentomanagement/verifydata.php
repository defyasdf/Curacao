<?php
	ini_set('display_errors',1);
	ini_set('max_execution_time', 300);
	error_reporting(E_ALL);
	require_once('../dbw.php');
	require_once('../urls.php');
	require_once('Mfunctions.php');
	$result = true;
	
	
	
	if($result == TRUE)
	{
	require_once(MAGE_FILE_URL_DEMO);
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
        $currentStore = Mage::app()->setCurrentStore(1);
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        	$collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
			 foreach ($collection as $product) 
			 {
				$sku = $product->getSku();
				echo $product->getSku()."-".$product->getVendorid()."<br>";
				if($product->getVendorid() == "2139")
				{
				$price = get_price_from_ar($sku);
																			// compare price start
																			if($price['product_retail'] != $product->getprice())
																			$price = TRUE;
																			else
																			$price = FALSE;
																			// compare price ends
																			// compare cost start
																			if($price['product_cost'] != $product->getcost())
																			$price = TRUE;
																			else
																			$price = FALSE;
																			// compare cost ends
				}
				else
				{
				$prices = get_price_from_table($sku);
				$price['product_retail'] = $prices->product_retail;
				$price['product_cost'] = $prices->product_cost;
																			// compare price start
																			if($price['product_retail'] != $product->getprice())
																			$priceretail = FALSE;
																			else
																			$priceretail = TRUE;
																			// compare price ends
																			// compare cost start
																			if($price['product_cost'] != $product->getcost())
																			$pricecost = FALSE;
																			else
																			$pricecost = TRUE;
																			// compare cost ends
				}
				if($priceretail)
				$product->setprice($price['product_retail']);
				if($pricecost)
				$product->setcost($price['product_cost']);
				try {
				$product->save();
			 	} catch (Exception $ex) {
				echo $ex->getMessage();
				}
exit;
			 }
			 			 
			 // try to save ends
			}
			
			
			
			// get price from AR
			function get_price_from_ar($sku)
			{
			$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
			$ns = 'http://lacuracao.com/WebServices/eCommerce/';
			$headerbody = array('UserName' => 'mike', 
								'Password' => 'ecom12'); 
			$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);  
			$h = $proxy->__setSoapHeaders($header); 
			
			$credit = $proxy->GetSkuPrice(array('Sku'=>$sku));
			
			$result = $credit->GetSkuPriceResult;
			
			$price_info = explode('|',$result);
			if($price_info[0]=='OK'){
				
				if($price_info[2]>0){
					$rebate_price = $price_info[1]-$price_info[3];
					if($rebate_price<$price_info[2]){
						$price = $rebate_price;
					}else{
						$price = $price_info[2];
					}
				}else{
					$price = $price_info[1]-$price_info[3];
				}
				
				$product['product_retail'] = $price;
				$product['product_cost'] = $price_info[6];
				return $product;
			}
			}
			
			// get price from AR
			
			
?>