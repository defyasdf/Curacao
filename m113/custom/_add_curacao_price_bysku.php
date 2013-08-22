<?php 
	// INI setting
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	//server DB connection
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';

	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);	
	//Magento Mage connection
	
	$mageFilename = '/var/www/dev/app/Mage.php';
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	umask(0);
	Mage::app('default'); 
	//Getting current store ID	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
//	echo 'magento works';
//	exit;	
	/*$sql = "select * from curacao_skus";
	$re = mysql_query($sql);*/
	$cnt = 1;
	$cnt1 = 0;	
	
	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	// Set headers
	$headerbody = array('UserName' => 'mike', 
						'Password' => 'ecom12'); 
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
			
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
		
	/*$collection = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*') // select all attributes
		->addAttributeToFilter('vendorid', '2139')
		->setCurPage(4); // set the offset (useful for pagination)
		
		// we iterate through the list of products to get attribute values
	
	foreach ($collection as $products) {	*/

	
		$product = Mage::getModel('catalog/product');
		$productId = $product->getIdBySku('17D-863-ECDV150FBPWU');

		$product->load($productId);
		//$price = file_get_contents("http://data.icuracao.com/custom/get_price.php?sku=".$products->getSku());	
	if($product->getCron()!=492){	
	
		$credit = $proxy->GetSkuPrice(array('Sku'=>'17D-863-ECDV150FBPWU'));
	
		$result = $credit->GetSkuPriceResult;
		
		$price_info = explode('|',$result);
		
		if($price_info[0]=='OK'){

			$product->setPrice($price_info[1]);
			
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
			if($price!=$price_info[1]){
				$product->setSpecialPrice($price);
				$product->setSpecialFromDate(date("Y-m-d", time() - 60 * 60 * 24));
				$product->setSpecialFromDateIsFormated(true);
				
				$product->setSpecialToDate(date("Y-m-d", time() + 60 * 60 * 24));
				$product->setSpecialToDateIsFormated(true);
			}else{
				$product->setSpecialPrice('');
			}
			
			$product->setRebate($price_info[3]);
				try {
					$product->save();
					echo $product->getId().$product->getSku().' Product added Price at '.$price;
					$cnt++;
					
				}
				catch (Exception $ex) {
					echo $ex->getMessage();
					$cnt1++;
				}
		}
			
		}
	