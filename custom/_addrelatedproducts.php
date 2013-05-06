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
	
	$sql = 'SELECT * FROM `addons`';
	$resu = mysql_query($sql);
	while($row = mysql_fetch_array($resu)){
		
		$_product = Mage::getModel('catalog/product');
		$_product->load($row['product_id']);
		
		$related = explode(',',$row['addons_ids']);
		$add = array();
		for($i=0;$i<sizeof($related);$i++){
			$add[$related[$i]] = array('position'=>$i+1)	;
		}
		
	
	
	
		$_product->setRelatedLinkData($add);
		//here ... some other product operations and in the end
		
		 try {
				$_product->save();
				echo 'Related Products have been added';
			 } catch (Exception $e) {
				 echo $e->getMessage();
			 }
			
		

}


?>