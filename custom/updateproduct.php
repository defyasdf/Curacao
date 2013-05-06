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
	$num = 1;
	$sql = "SELECT * FROM `englishready` WHERE `product_description` != ''";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)){
		$sql1 = 'select * from finalproductlist where product_sku = "'.$row['product_sku'].'" and magento_product_id != ""';
		$result1 = mysql_query($sql1);
		$row1 = mysql_fetch_array($result1);
		
		$es = 'select * from spenishready where product_sku = "'.$row['product_sku'].'"';
		$esresult = mysql_query($es);
		$esrow = mysql_fetch_array($esresult);
		
		
		$product = Mage::getModel('catalog/product');
		
		$product->load($row1['magento_product_id']);
		
		$list = get_html_translation_table(HTML_ENTITIES);
		
		unset($list['"']);
		unset($list['<']);
		unset($list['>']);
		unset($list['&']);
		
		$search = array_keys($list);
		$values = array_values($list);
		
		$name = $row['prduct_name'];
		$short = $row['product_description'];
		$full = $row['product_description'];
		$feature = str_replace($search, $values, $row['product_features']);
		$spacs = str_replace($search, $values, $row['product_specs']);
		
		
		//$str_out = str_replace($search, $values, $str_in);
		
		$esname = str_replace($search, $values, $esrow['prduct_name']);
		$esshort = str_replace($search, $values, $esrow['product_description']);
		$esfull = str_replace($search, $values, $esrow['product_description']);
		$esfeature = str_replace($search, $values, $esrow['product_features']);
		$esspacs = str_replace($search, $values, $esrow['product_specs']);
				
		
		$product->setName($name);
		$product->setShortDescription(htmlspecialchars_decode($short,ENT_QUOTES));
		$product->setDescription(htmlspecialchars_decode($full,ENT_QUOTES));
		$product->setFeature(htmlspecialchars_decode($feature,ENT_QUOTES));
		$product->setSpec001(htmlspecialchars_decode($spacs,ENT_QUOTES));
		
		$img = "http://www.lacuracao.com/images/".$row['product_img_path'];
		
		if(trim($img) != ''){
					if($img != 'http://www.lacuracao.com/images/NULL'){
						if(file_get_contents(trim($img))){	
							$url = trim($img);
							$image = '../media/images/'.str_replace('/','_',$row['product_sku']).'.jpg';
							file_put_contents($image, file_get_contents($url));
							$product->addImageToMediaGallery ($image, array('image','small_image','thumbnail'), false, false); 
					}
					}
		}
		
		
		try {
			
		    $product->save();
			
		}
		catch (Exception $ex) {
		    echo $ex->getMessage();
		}
		
		$product->setStoreId(3)->load($row1['magento_product_id']);
		$product->setName($esname);
		$product->setShortDescription($esshort);
		$product->setDescription($esfull);
		$product->setFeature(htmlspecialchars_decode($esfeature,ENT_QUOTES));
		$product->setSpec001(htmlspecialchars_decode($esspacs,ENT_QUOTES));
		
		try {
		    $product->save();
			echo '('.$num.')Product Saved';
		}
		catch (Exception $ex) {
		    echo $ex->getMessage();
		}
		
		$num++;		
		
	}