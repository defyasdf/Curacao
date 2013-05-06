<?php
	
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	ini_set('max_execution_time', 0);	
	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct');
	
	$sql1 = "SELECT * FROM `magentoque` where mstatus = 1";
	$resu1 = mysql_query($sql1);
	$num = 1;
	while($row1 = mysql_fetch_array($resu1)){	
	
		$sql = 'select * from finalproductlist where fpl_id = '.$row1['finalproId'];
		$result = mysql_query($sql);
		
		$row = mysql_fetch_array($result);
		
	
		$mage_cat_ids = explode('-',$row['magento_category_id']);
		$final_cat = array();
		
		for($i=0;$i<sizeof($mage_cat_ids);$i++){
			if(trim($mage_cat_ids[$i])!=''){
				$final_cat[] = $mage_cat_ids[$i];
				
			}
		}
		$cats = implode(',',$final_cat);
	
		$product = Mage::getModel('catalog/product');
		
		$product->load($row['magento_product_id']);
		
		$product->setCategoryIds($final_cat);
		
		try {
				$product->save();
					
			}
			catch (Exception $ex) {
				echo $ex->getMessage();
			}	
			
			echo '('.$num.')category saved';
			$num++;
	}