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
	
	$mageFilename = '/var/www/html/app/Mage.php';
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
	
	
		
	$collection = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*') // select all attributes
		->addAttributeToFilter('vendorid', '6987')
		->addAttributeToFilter('status', '1')		
		->setCurPage(4); // set the offset (useful for pagination)
		
		// we iterate through the list of products to get attribute values
	
	foreach ($collection as $products) {	

	
		$product = Mage::getModel('catalog/product');
		$product->load($products->getId());
		//$price = file_get_contents("http://data.icuracao.com/custom/get_price.php?sku=".$products->getSku());	
	if($product->getCron()!=492){	
		
		$product->setVisibility(1);
		$product->setStatus(2);
		$product->setStockData(array('manage_stock'=>1, 'is_in_stock' => 0, 'qty' => 0));
			
				try {
					$product->save();
					echo $product->getId().' Product updated ';
					$cnt++;
					
				}
				catch (Exception $ex) {
					echo $ex->getMessage();
					$cnt1++;
				}
		
			
		}
		
		
	
	}
	
	echo $cnt." Products has been updated and ".$cnt1." Products has been skipped";
	
	$query = "insert into cronstatus(cronday, status) values('".date('Y-m-d')."','1')";
	mysql_query($query) or die(mysql_error());