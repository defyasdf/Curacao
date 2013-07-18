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
	$mageFilename = '/var/www/html/app/Mage.php';
	include('/var/www/html/custom/360pi/Net/SFTP.php');
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
 	
	
	
	$collection = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*') // select all attributes
		->addAttributeToFilter('status', 1)
		->setCurPage(4); // set the offset (useful for pagination)
		
		// we iterate through the list of products to get attribute values
		$j = 1;
		foreach ($collection as $product) {
			
//			echo "(".$j.")".$product->getSku()."<br>";
			
			$cat_ids = Mage::getResourceSingleton('catalog/product')->getCategoryIds($product);
			$cName = array();
			for($i = 0;$i<(sizeof($cat_ids)-1);$i++){
				$cName[] = Mage::getModel('catalog/category')->load($cat_ids[$i])->getName();
			}		
			$cat = implode('_',$cName);					
			
			$url = 'http://www.icuracao.com/'.$product->getUrlPath();
			
			$qtyStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();		

		    $image  = Mage::getModel('catalog/product_media_config')
            ->getMediaUrl( $product->getImage() );
			
			if($product->getVendorid()=='2139'){			
				$mo = explode("-",$product->getSku());
				if(sizeof($mo)==3){
					$model = $mo[2];
				}else{
					$model = $product->getSku();
				}
			}else{
				$mo = explode("-",$product->getSku());
				if(sizeof($mo) == 3){
					if($mo[0]=='cur'){
						$model = $mo[2];
					}else{
						$model = $product->getSku();
					}
				}else{
					$model = $product->getSku();
				}
			}
			if($mo)
			
			//$data[] = array( "product_id"=>$product->getId(),"name"=>$product->getName(),"Model"=>$model, "sku"=>$product->getSku(),"UPC"=>$product->getUpc(),"URL"=>$url,"Image_URL"=>$image,"category_tree"=>$cat,"QTY"=>$qtyStock, "price"=>$product->getPrice(), "Special_price"=>$product->getSpecialPrice(), "Cost_price"=>$product->getCost(),"shipping"=>$product->getShprate(),"Status"=>$product->getStatus());
			
			$data[] = array( '"product_id"'=>'"'.$product->getId().'"','"name"'=>'"'.str_replace('"','',$product->getName()).'"','"Model"'=>'"'.$model.'"', "Brand"=>$product->getAttributeText('tv_brand'), '"sku"'=>'"'.$product->getSku().'"','"UPC"'=>'"'.$product->getUpc().'"','"URL"'=>'"'.$url.'"','"Image_URL"'=>'"'.$image.'"','"category_tree"'=>'"'.$cat.'"','"QTY"'=>'"'.$qtyStock.'"', '"price"'=>'"'.$product->getPrice().'"', '"Special_price"'=>'"'.$product->getSpecialPrice().'"', '"Cost_price"'=>'"'.$product->getCost().'"','"shipping"'=>'"'.$product->getShprate().'"','"Status"'=>'"'.$product->getStatus().'"');
			
			  $j++;

		}
	

	$filename = "Curacao_Active_Products_".date('Y_m_d').".csv";
	$content = '';
	//  header("Content-Disposition: attachment; filename=\"$filename\"");
	//  header("Content-Type: text/csv");
	
	  $flag = false;
	  foreach($data as $row) {
	
		if(!$flag) {
		  // display field/column names as first row
		 $content .= implode(",", array_keys($row)) . "\r\n";
		  $flag = true;
		}
	   // array_walk($row, 'cleanData');
		$content .= implode(",", array_values($row)) . "\r\n";
	  }
	  
	file_put_contents($filename, $content);
	
	$sftp = new Net_SFTP('ftp.gazaro.com');
	if (!$sftp->login('lacuracao', 'XMQEGcBk8IDX')) {
		exit('Login Failed');
	}
	//echo 'Login ';
	if($sftp->put('uploads/'.$filename, $filename, NET_SFTP_LOCAL_FILE)){
		echo "successfully uploaded $filename\n";
		unlink($filename);
		exit;
	} else {
		echo "There was a problem while uploading $file\n";
		exit;
	}
	 
	
	
	
	