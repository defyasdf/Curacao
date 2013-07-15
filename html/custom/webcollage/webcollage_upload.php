<?php
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","2048M");
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);	
	
	$mageFilename = '/var/www/m113/app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
 	
	$cat = Mage::getModel('catalog/category')->load(466);
	$subcatcollection = $cat->getChildren();
	
	$subcat = explode(",",$subcatcollection);
	
	$cat_collecton = $subcat;
	$final_list = array();
	for($i=0;$i<sizeof($cat_collecton);$i++){
		$scat = $cat = Mage::getModel('catalog/category')->load($cat_collecton[$i]);
		$scatcollection = $scat->getChildren();
		if(trim($scatcollection)!=''){
			$sucat = explode(",",$scatcollection);
			$final_list = array_merge($cat_collecton,$sucat);
		}
	}
	
	$collection = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*') // select all attributes
		->addAttributeToFilter('status', 1)
		->addAttributeToFilter('tv_brand', array('in' => array(325,76,472,178,237,25,171,136,85,75,180,140,84,60,642,20,470,35,108,207,28,27,109,694,693,691,690,689,687,695,686,685,684,692)))
		->setCurPage(4); // set the offset (useful for pagination)
		
		// we iterate through the list of products to get attribute values
		$j = 1;
		foreach ($collection as $product) {
			
//			echo "(".$j.")".$product->getSku()."<br>";
			
			$cat_ids = Mage::getResourceSingleton('catalog/product')->getCategoryIds($product);
			$cName = array();
			$cat0 = '';
			$cat1 = '';
			$cat2 = '';
			$cat3 = '';
			$cat4 = '';
			$c = 'cat';
			for($i = 0;$i<(sizeof($cat_ids)-1);$i++){
				if($cat_ids[$i] != 424){
				if(!in_array($cat_ids[$i],$final_list)){
					$cName[] = Mage::getModel('catalog/category')->load($cat_ids[$i])->getName();
					//$$c.$i 
					$ca = $c.$i;
					$$ca= Mage::getModel('catalog/category')->load($cat_ids[$i])->getName();
				}
				}
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
					if($mo[0]=='cur'){
						$model = $mo[sizeof($mo)-1];
					}else{
						$model = $product->getSku();
					}						
				}
			}
		//	if($mo)
		
		$vendor = '';
		
		if($product->getVendorid()!='2139'){
			$s = 'select * from vendormanagement where vendorID = "'.$product->getVendorid().'"';
			$r = mysql_query($s);
			$rw = mysql_fetch_array($r);
			$vendor = $rw['vendorName'];
		}
		
			
			$data[] = array( "product_id"=>$product->getId(),"Brand"=>$product->getAttributeText('tv_brand'),"Manufacturer Part"=>$model,"UPC"=>$product->getUpc(),"Product_Name"=>$product->getName(), "Curacao Product sku"=>$product->getSku(),"price"=>$product->getPrice(),"URL"=>$url,"Comments"=>$vendor);
			
			  $j++;

		}
	

	$filename = "All_Brands_WebCollage.csv";
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
	  
	
	//exit;	
	  
	file_put_contents($filename, $content);
	
	$ftp_server="feeds.webcollage.net";
	$ftp_user_name="icuracao";
	$ftp_user_pass="8pwN8mm*";
	 $file = $filename;//tobe uploaded
	$remote_file = $filename;
	
	 // set up basic connection
	$conn_id = ftp_connect($ftp_server);
	
	 // login with username and password
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	
	 // upload a file
	 if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
		echo "successfully uploaded $file\n";
		exit;
	 } else {
		echo "There was a problem while uploading $file\n";
		exit;
	}
	 // close the connection
	 ftp_close($conn_id);
	
	exit;	
	
	