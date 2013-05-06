<?php

	//////////////////////////////	//////////////////////////////	//////////////////////////////	//////////////////////////////	//////////////////////////////
	//die();
	//////////////////////////////	//////////////////////////////	//////////////////////////////	//////////////////////////////	//////////////////////////////
	//////////////////////////////	//////////////////////////////	//////////////////////////////	//////////////////////////////	//////////////////////////////
	//////////////////////////////	//////////////////////////////	//////////////////////////////	//////////////////////////////	//////////////////////////////
	//////////////////////////////	//////////////////////////////	//////////////////////////////	//////////////////////////////	//////////////////////////////

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


	echo('<br/>'.count($results).' Found<hr/>');
	
	$qty_removed = 0;
	
	$file_path = '/var/www/html/media/catalog/product';

	
	foreach($results as $data) { 
			
		$fname = $data['value'];
		$fname = substr($fname, 0, strpos($fname, '.jpg')  ); //remove the extension (may have to re-run for PNG?)

		$fkey = $fname;
		
		if( strrpos($fname, '_') > 0 ){
			$fkey = substr($fname, 0, strrpos($fname, '_'));
		}

		
		//echo($fname.'<br/>');
		
		$_file_query = 'SELECT * FROM  ' . $resource->getTableName('catalog_product_entity_media_gallery') . ' WHERE `attribute_id`=88 AND  `value` LIKE  "'.$fkey.'%" ORDER BY value ASC';
		$_fq_results = $resource->getConnection('core_read')->fetchAll($_file_query);
		
//		echo(count($_fq_results).'<br>');
		
		if( count($_fq_results) > 1){

			$i = 0;

			foreach($_fq_results as $data):
			
				if(($i+1) == count($_fq_results)){
					echo($fname . ' -> KP: ' . $data['value'].'<br/><br/>');	//keep me
				} else {
					echo($fname . ' -> rm: ' . $data['value'].'<br/>');			//RM me
					
					$qty_removed++;
						
					$id = $data['value_id'];
					$write->query('DELETE FROM `catalog_product_entity_media_gallery` WHERE `value_id` = "'.$id.'"');

					if( $file_path.file_exists($data['value']) ) {
						unlink( $file_path.$data['value'] );
					}
				}
				$i++;
			endforeach;
		}

	}

	echo('<br/>'.$qty_removed.' Removed<br/>');
	
?>