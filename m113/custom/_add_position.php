<?php 

	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
	echo 'mage works';
	exit;
	$sql = 'SELECT `catid` FROM `positioning` GROUP BY `catid`';
	$resu = mysql_query($sql);
	while($row = mysql_fetch_array($resu)){
	
		$category = new Mage_Catalog_Model_Category();
		$category->load($row['catid']); //My cat id is 10
		
		/*$prodCollection = $category->getProductCollection()
						->addAttributeToSort('position');*/
						
	//	$i = 1;					
		$pr = array();
		$q = 'SELECT * FROM `positioning` WHERE `catid` = '.$row['catid'];
		$re = mysql_query($q);
		while($rq = mysql_fetch_array($re)){
//		foreach ($prodCollection as $product) {
	//		$positions = $category->getProductsPosition();
		//	$productId = $product->getID();
			
			
			/*if (!isset($positions[$productId])) {
				 echo 'product_not_assigned';
			 }*/
			
			 $pr[$rq['proid']] = $rq['position'];
		}

		$category->setPostedProducts($pr);
	 
			 try {
				 $category->save();
				//echo 'cat saved'; 
				
			 } catch (Exception $e) {
				 echo $e->getMessage();
			 }

	}
	
	echo " Products has been updated and Products has been skipped";
	