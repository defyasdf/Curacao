<?php
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","2048M");
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'cdc';
	
	
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
			
			if($product->getPrice() == ''){
			
				/*$sql = 'select * from directmagentotable_jewellery where product_sku = "'.$product->getSku().'"';
				$resu = mysql_query($sql);
				if(mysql_num_rows($resu)>0){
					$row = mysql_fetch_array($resu);
					$_product = Mage::getModel('catalog/product');
					$_product->load($product->getId());
					$_product->setPrice($row['product_retail']);
					$_product->setCost($row['product_cost']);
					$_product->setVendorid('9116');
					try {
							$_product->save();
							echo $_product->getSku().' Product been updated with price '. $row['product_retail'];
								
						}
						catch (Exception $ex) {
							echo $ex->getMessage();
		
						}
					
				}*/
			
			
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
					if($mo[0]=='cur'){
						$model = $mo[sizeof($mo)-1];
					}else{
						$model = $product->getSku();
					}						
				}
			}
		//	if($mo)
			
			$data[] = array( "product_id"=>$product->getId(),"name"=>$product->getName(),"Model"=>$model, "sku"=>$product->getSku(),"UPC"=>$product->getUpc(),"URL"=>$url,"Image_URL"=>$image,"category_tree"=>$cat,"QTY"=>$qtyStock, "Brand"=>$product->getAttributeText('tv_brand'), "price"=>$product->getPrice(), "Special_price"=>$product->getSpecialPrice(), "Cost_price"=>$product->getCost(),"shipping"=>$product->getShprate(),"Status"=>$product->getStatus());
			
			  $j++;
			}
		}
	//echo 'Done';

	  $filename = "Magento_Products_without_price.xls";
	  $content = '';
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
	
