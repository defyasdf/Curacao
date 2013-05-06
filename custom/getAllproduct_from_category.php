<?php
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
 	
//	$cat = array(42, 43);
	
	//for($i=0;$i<2;$i++){	

	$category = new Mage_Catalog_Model_Category();
	$category->load(67); //My cat id is 10
	$prodCollection = $category->getProductCollection();


	foreach ($prodCollection as $product) {
		$_product = Mage::getModel('catalog/product');
		$_product->load($product->getId());
		$param = array(
				   2281=>array(
						  'position'=>0
					)					
				);
		$_product->setRelatedLinkData($param);
		//here ... some other product operations and in the end
		$_product->save();
		
		echo $product->getId().'Related Products been added';
		
		//exit;			
		}
	//}


//print_r($prdIds);