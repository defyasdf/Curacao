<?php
	ini_set('display_errors',1);
	ini_set('max_execution_time', 300);
	error_reporting(E_ALL);
	require_once('../dbw.php');
	require_once('../urls.php');
	require_once('Mfunctions.php');
	// $fpl_id = $_GET['fpl_id'];
	$result = true;
	
	// removing special Character data strt
			$list = get_html_translation_table(HTML_ENTITIES);
			unset($list['"']);
			unset($list['<']);
			unset($list['>']);
			unset($list['&']);		
			$search = array_keys($list);
			$values = array_values($list);
			// removing special Character data end
	
	if($result == TRUE)
	{
	require_once(MAGE_FILE_URL_DEMO);
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
        $currentStore = Mage::app()->setCurrentStore(1);
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
			$contents = get_cosmetices_data();

			while($content = mysql_fetch_object($contents))
			{
			$content->product_sku."<br>";
			$skunospace = str_replace("21 ","21",$content->product_sku);
        	$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$skunospace);
			if($product)
			{
			// set URL key start
			$newurl = htmlspecialchars_decode($content->prduct_name,ENT_QUOTES);
			$creaturl = cleaningdata($newurl);
			$strlower = strtolower($creaturl);
			$url = str_replace(" ","-",$strlower);
			$product->seturl_key($url);
			// set URL key end
			 $product->setSku($content->product_sku);
			 // get price from AR and insert in system start
			  

			
			//Set the soap client
			$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
			$ns = 'http://lacuracao.com/WebServices/eCommerce/';
			// Set headers
			$headerbody = array('UserName' => 'mike', 
								'Password' => 'ecom12'); 
			//Create Soap Header.        
			$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
					
			//set the Headers of Soap Client. 
			$h = $proxy->__setSoapHeaders($header); 
			
			/*$credit = $proxy->ECCreditLimit(array('Cust_ID'=>$_GET['custnum']));
			
			$result = $credit->ECCreditLimitResult;
			
			echo $result;
			*/
			
			$credit = $proxy->GetSkuPrice(array('Sku'=>$content->product_sku));
			
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
				
				$productprice = $price;
				$productcosting = $price_info[6];
				
			}

				echo $productprice."<br>";
				echo $productcosting;
			 // get price from AR and insert in system ends
			 if($productprice)
			 $product->setPrice($productprice);
			 if($productcosting)
			 $product->setcost($productcosting);
			 // try to save start
			 try {
				$product->save();
			 	} catch (Exception $ex) {
				echo $ex->getMessage();
			}
			 // try to save ends
			}
			else
			{
			echo "No need to do anything";
			}
			exit;
			}
			}
?>