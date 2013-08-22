<?php
	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_production';
	
	
	
	$link = mysql_connect($server,$user,$pass);
	$link1 = mysql_connect($server,$user,$pass,true);
	
	mysql_select_db($db,$link) or die("No DB");	
	mysql_select_db('icuracaoproduct',$link1) or die("No DB");	


	$mageFilename = '/var/www/upgrade/app/Mage.php';
	
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
	
	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	// Set headers
	$headerbody = array('UserName' => 'mike', 
						'Password' => 'ecom12'); 
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
			
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 

	
	
	$collection = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*') // select all attributes
		->addAttributeToFilter('status', 1)
		->setCurPage(4); // set the offset (useful for pagination)
		
		// we iterate through the list of products to get attribute values
		$j = 1;
		foreach ($collection as $product) {
			
			$start_ts = strtotime($product->getSpecialFromDate());
			$end_ts = strtotime($product->getSpecialToDate());
			$user_ts = strtotime(date('Y-m-d'));
			
			$rebate = '';
			$ar_price = '';
			$ar_cost = '';
			$ar_special = '';
			if($product->getVendorid()=='2139'){
				$credit = $proxy->GetSkuPrice(array('Sku'=>$product->getSku()));
				$result = $credit->GetSkuPriceResult;
				$price_info = explode('|',$result);
				
				if($price_info[0]=='OK'){
					$rebate= $price_info[3];
					$ar_cost = $price_info[6];
					$ar_price = $price_info[1];
					$ar_special = $price_info[2];
				}
			}
			$cost = $product->getCost();
			if(trim($rebate) != ''){
				$cost = $product->getCost() - $rebate;
			}
			
			if((($user_ts >= $start_ts) && ($user_ts <= $end_ts))){
				$margin = $product->getSpecialPrice()-$product->getCost();	
				$realmargin = $product->getSpecialPrice()-$cost;
				$price = $product->getSpecialPrice();
			}else{
				$margin = $product->getPrice()-$product->getCost();
				$realmargin = $product->getPrice()-$cost;
				$price = $product->getPrice();
			}
			if($product->getPrice()>0){
				$percent = $margin/$product->getPrice();
				$percent = $percent*100;
				if($price>0){
					$rpercentage = $realmargin/$price;
				}else{
					$rpercentage = $realmargin/$product->getPrice();
				}
				$rper = number_format( $rpercentage*100, 2 ) . '%'; 
			}else{
				$percent = '';
			}
			$per = $percent;
			$percent = number_format( $percent, 2 ) . '%';
			if($per<=5){

			$sql = "SELECT cc . * FROM catalog_category_entity cc JOIN catalog_category_product cp ON cc.entity_id = cp.category_id WHERE cp.product_id = ".$product->getId()." AND cc.path NOT LIKE '1/2/466%' ORDER BY `cc`.`level` DESC";
			$result = mysql_query($sql,$link);
			$row = mysql_fetch_array($result);
			
			$cat_id = str_replace('1/2/','',$row['path']);
			$cattree = explode('/',$cat_id);
			$cName = array();
			
			for($j=0;$j<sizeof($cattree);$j++){
				if(!in_array($cattree[$j],$final_list)){
					$cName[] = Mage::getModel('catalog/category')->load($cattree[$j])->getName();
				}
			}
			$cat = implode('_',$cName);					

			$qtyStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();		
			$s = explode('-',$product->getSku());
			if(sizeof($s)==3){
				if($s[0]=='cur'){
					$sku = $s[2];
				}else{
					$sku = $product->getSku();
				}
			}else{
				if($s[0]=='cur'){
						$sku = $s[sizeof($s)-1];
					}else{
						$sku = $product->getSku();
					}
			}
			
			$sql = "SELECT product_upc FROM `masterproducttable` WHERE `product_sku` = '".$sku."' limit 0,1";
			$result = mysql_query($sql,$link1);
			$row = mysql_fetch_array($result);
			
			if($product->getVendorid()!=''){
				$vsql = "SELECT vendorName FROM `vendormanagement` WHERE `vendorID` = '".$product->getVendorid()."'";
				$vresult = mysql_query($vsql,$link1);
				$vrow = mysql_fetch_array($vresult);
			}
			if($product->getStatus() == '1'){
				$status = 'Enable';
			}else{
				$status = 'Disabled';
			}
			
			
			$data[] = array( "Status"=>$status,"sku"=>$product->getSku(),"name"=>$product->getName(), "UPC"=>$row['product_upc'],"category_tree"=>$cat,"Onhand_QTY"=>$qtyStock,"Vendor"=>$vrow['vendorName'],"Cost_price"=>$product->getCost(),"Special_price"=>$product->getSpecialPrice(), "Retail_price"=>$product->getPrice(),"Margin%"=>$percent,"Margin($)"=>$margin,"Real_Margin(%)"=>$rper,"Real_Margin($)"=>$realmargin,"Rebate"=>$rebate, "AR_Price"=>$ar_price,"AR_Cost"=>$ar_cost,"AR_Special"=>$ar_special,"Price_real"=>$price, "shipping"=>$product->getShprate(), "Special_From_date"=>$product->getspecial_from_date(),"Special_To_date"=>$product->getspecial_to_date());
			}
			  $j++;

		}

	 $filename = "icuracao_nagative_margin_report.xls";
	 $content = '';
	// header("Content-Disposition: attachment; filename=\"$filename\"");
//	 header("Content-Type: application/vnd.ms-excel");
	
	  $flag = false;
	  foreach($data as $row) {
	
		if(!$flag) {
		  // display field/column names as first row
		  $content .= implode("\t", array_keys($row)) . "\r\n";
		  $flag = true;
		}
	   // array_walk($row, 'cleanData');
		$content .= implode("\t", array_values($row)) . "\r\n";
	  }
	//echo $content;	
	
	//exit;	//*/
	
	
	file_put_contents($filename, $content);
	
	$from = "Inventory Report <inventoryReport@icuracao.com>"; 
    $subject = date("d.M H:i")." Dailly Inventory Report"; 
    $message = date("Y.m.d H:i:s")."\n Please find attached file with today's Inventory Report";
    $headers = "From: $from";
 
    // boundary 
    $semi_rand = md5(time()); 
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
 	
	$files = array('icuracao_nagative_margin_report.xls');
	
    // headers for attachment 
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
 
    // multipart boundary 
    $message = "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n"; 
 
    // preparing attachments
    for($i=0;$i<count($files);$i++){
        if(is_file($files[$i])){
            $message .= "--{$mime_boundary}\n";
            $fp =    @fopen($files[$i],"rb");
        $data =    @fread($fp,filesize($files[$i]));
                    @fclose($fp);
            $data = chunk_split(base64_encode($data));
            $message .= "Content-Type: application/octet-stream; name=\"".basename($files[$i])."\"\n" . 
            "Content-Description: ".basename($files[$i])."\n" .
            "Content-Disposition: attachment;\n" . " filename=\"".basename($files[$i])."\"; size=".filesize($files[$i]).";\n" . 
            "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
            }
        }
    $message .= "--{$mime_boundary}--";
    $returnpath = "-f sanjay@icuracao.com" ;
    $ok = @mail('Reuben.Simantob@icuracao.com, mikeaz@icuracao.com, rdrori@aol.com', $subject, $message, $headers, $returnpath); 
	 if($ok){ echo 'Sent'; } else { echo 'not sent'; }
	
