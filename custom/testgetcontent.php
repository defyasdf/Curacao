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
 
	//$path = getcwd().'/CategoryProducts.csv';
	//$readfile = file ($path);
	
	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct');
	
	$product = Mage::getModel('catalog/product');
	
	$sql = "SELECT * FROM `sku_img` where status = 0";
	$result = mysql_query($sql);	
	while($row = mysql_fetch_array($result)){
		$product->load($row['id']);
		$img = explode(',',$row['imgs']);
			for($i=0;$i<sizeof($img);$i++){
				if(trim($img[$i]) != ''){
					if($img[$i] != 'http://www.lacuracao.com/images/NULL'){
					//if(file_exists(trim($img[$i]))){
						if(file_get_contents(trim($img[$i]))){	
							//$s = $img[$i];
							$url = trim($img[$i]);
							$image = '../media/images/'.str_replace('/','_',$row['sku']).'_'.$i.'.jpg';
							file_put_contents($image, file_get_contents($url));
							$product->addImageToMediaGallery ($image, array('image','small_image','thumbnail'), false, false); 
														
						//}
					}
					}
				}
			}
			
			try {
			
			    $product->save();
				
				$q = 'update sku_img set status = 1 where skuid = '.$row['skuid'];
				mysql_query($q) or die(mysql_error());
					
			}
			catch (Exception $ex) {
				echo $ex->getMessage();
			}
			
	}
	
	