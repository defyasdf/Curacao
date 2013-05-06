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
			
	$query 		= "SELECT * FROM `catalog_product_entity_varchar` WHERE attribute_id in (85,86,87) AND value='no_selection'";
	$results 	= $resource->getConnection('core_read')->fetchAll($query);
	

	foreach($results as $row){
    	$id = $row["entity_id"];

	    $product = Mage::getModel("catalog/product")->load($id);
    	$mediaGallery = $product['media_gallery'];
	    
		
		if( count($mediaGallery['images']) > 0 ){

		    if ($mediaGallery['images'][0]['file'] != '' || $mediaGallery['images'][0]['file'] != NULL) {
    		    $path = $mediaGallery['images'][0]["file"];        	
				$update_result = $write->query("UPDATE `catalog_product_entity_varchar` SET VALUE='".$path."' WHERE attribute_id in (85,86,87) and entity_id=".$id);
	        	echo "set images for product ".$id."<br />";
	    	}
	    }


	}

?>