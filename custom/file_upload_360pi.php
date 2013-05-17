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
				if($mo[0]=='cur'){
					$model = $mo[2];
				}else{
					$model = $product->getSku();
				}
			}
			if($mo)
			
			$data[] = array( "product_id"=>'"'.$product->getId().'"',"name"=>'"'.$product->getName().'"',"Model"=>'"'.$model.'"', "sku"=>'"'.$product->getSku().'"',"UPC"=>'"'.$product->getUpc().'"',"URL"=>'"'.$url.'"',"Image_URL"=>'"'.$image.'"',"category_tree"=>'"'.$cat.'"',"QTY"=>'"'.$qtyStock.'"', "price"=>'"'.$product->getPrice().'"', "Special_price"=>'"'.$product->getSpecialPrice().'"', "Cost_price"=>'"'.$product->getCost().'"',"shipping"=>'"'.$product->getShprate().'"',"Status"=>'"'.$product->getStatus().'"');
			
			  $j++;

		}
	

	$filename = "Magento_Active_Products_".date('Y_m_d').".csv";
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
	
	$ftp_server="lb-ftp.logictec.com";
	$ftp_user_name="snprajapati";
	$ftp_user_pass="JxO5Ij6JKCXeu4F";
	 $file = $filename;//tobe uploaded
	$remote_file = $filename;
	
	 // set up basic connection
	$conn_id = ftp_connect($ftp_server);
	
	 // login with username and password
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	
	 // upload a file
	 $path = "./";
	 $check_file_exist = $path.$filename;
	 
	 $contents_on_server = ftp_nlist($conn_id, $path);
	 
	 if (in_array($check_file_exist, $contents_on_server)) 
	 {
		ftp_delete($conn_id, $filename);
		
	 }
	 if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
		echo "successfully uploaded $file\n";
		unset($file);
		exit;
	 } else {
		echo "There was a problem while uploading $file\n";
		exit;
	}
	 // close the connection
	 ftp_close($conn_id);
	
	exit;	
	
	