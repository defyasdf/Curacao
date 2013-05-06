<?php

	die();

	error_reporting(E_ALL | E_STRICT);
	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	ini_set('display_errors', 1);
	umask(0);
	Mage::app('default'); 
	

    $resource 	= Mage::getSingleton('core/resource');
	$write 		= Mage::getSingleton('core/resource')->getConnection('core_write');
			
	$query 		= 'SELECT * FROM ' . $resource->getTableName('catalog_product_entity_media_gallery');
	$results 	= $resource->getConnection('core_read')->fetchAll($query);


	echo('<br/>'.count($results).' Found<br/>');
	
	$qty_removed = 0;
	
	$file_path = '/var/www/html/media/catalog/product';
	
	foreach($results as $data) { 
			
		if(! $file_path.file_exists($data['value']) ) {
			echo('rem: '.$file_path.$data['value'].'<br/>');
			$id = $data['value_id'];
			
			//$write->query("DELETE FROM `catalog_product_entity_media_gallery` WHERE `value_id` = ".$id);

			$qty_removed++;
		} else {
			//file exists -- let's check the file size
			$id = $data['value_id'];
			$img_size = getimagesize($file_path.$data['value']);
			
			if($img_size[0] < 100){
				echo('h: '.$img_size[0].' w: '.$img_size[1].'<br/>');
			
				$write->query("DELETE FROM `catalog_product_entity_media_gallery` WHERE `value_id` = ".$id);
			}
			
		} 
	}
	
	echo('<br/>'.$qty_removed.' Removed<br/>');
	
?>