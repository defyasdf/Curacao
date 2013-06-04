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
	$mageFilename = '../app/Mage.php';
	
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
	if(sizeof($final_list)==0){
		$final_list = $cat_collecton;
	}
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
			
			$data[] = array( "product_id"=>$product->getId(),"name"=>$product->getName(),"Model"=>$model, "sku"=>$product->getSku(),"UPC"=>$product->getUpc(),"URL"=>$url,"Image_URL"=>$image,"category_tree"=>$cat,"Category_1"=>$cat0,"Category_2"=>$cat1,"Category_3"=>$cat2,"Category_4"=>$cat3,"Category_5"=>$cat4,"QTY"=>$qtyStock, "Brand"=>$product->getAttributeText('tv_brand'), "price"=>$product->getPrice(), "Special_price"=>$product->getSpecialPrice(), "Cost_price"=>$product->getCost(),"shipping"=>$product->getShprate(),"Status"=>$product->getStatus());
			
			  $j++;

		}
	

	 $filename = "Magento_Active_Products.xls";
	
	  header("Content-Disposition: attachment; filename=\"$filename\"");
	  header("Content-Type: application/vnd.ms-excel");
	
	  $flag = false;
	  foreach($data as $row) {
	
		if(!$flag) {
		  // display field/column names as first row
		  echo implode("\t", array_keys($row)) . "\r\n";
		  $flag = true;
		}
	   // array_walk($row, 'cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	  }
	
	exit;	
	  
	file_put_contents($filename, $content);
	
	$ftp_server="198.101.163.121";
	$ftp_user_name="snprajapati";
	$ftp_user_pass="JxO5Ij6JKCXeu4F";
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
	
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'icuracaoproduct';
	
	
	$link = mysql_connect($server,$user,$pass);
	
	mysql_select_db($db,$link);
	$n = 1;
	$m = 1;
	$sql = "SELECT * FROM `finalproductlist` WHERE `magento_product_id` != '' AND magento_product_id !=0";
	$result = mysql_query($sql);
	while($row=mysql_fetch_array($result)){
		$product = Mage::getModel('catalog/product');
		$product->load($row['magento_product_id']);
		if(sizeof($product->getSmallImage())){
			//echo sizeof($product->getSmallImage());
			if($row['product_img_path']!=''){
				$m++;
			}	
			$n++;
		}
		
//		exit;
	}
	
	echo $n .'No Image In Magento'. $m . ' No Image in Database'; ;