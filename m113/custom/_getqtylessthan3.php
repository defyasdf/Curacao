<?php

	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_magento';
	
	
	
	$link = mysql_connect($server,$user,$pass);
	$link1 = mysql_connect($server,$user,$pass,true);
	
	mysql_select_db($db,$link) or die("No DB");	
	mysql_select_db('icuracaoproduct',$link1) or die("No DB");	


	
	$mageFilename = '/var/www/m113/app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('admin'); 
	
	$collection = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*') // select all attributes
		->addAttributeToFilter('status', 1)
		->setCurPage(4); // set the offset (useful for pagination)
	// Proxy 
	
	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	$headerbody = array('UserName' => 'mike', 
                    'Password' => 'ecom12'); 
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
		
	foreach ($collection as $product) {
		$qtyStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();	
		if($qtyStock<4){
			$credit = $proxy->InventoryLevel(array('cItem_ID'=>$product->getSku(),'cLocations'=>'16,33'));
			$result = $credit->InventoryLevelResult;
			$s = explode("\\",$result);
			$inv16 = 0;
			$inv33 = 0;
			$invtotal = 0; 
			for($i=0;$i<sizeof($s);$i++){
				$in = explode("|",$s[$i]);				
				if($in[0]=='16'){
					$inv16 = $in[1];
				}elseif($in[0]=='33'){
					$inv33 = $in[1];
				}else{
					$invtotal = $in[1];
				}
			}
			
			$data[] = array( "product_id"=>$product->getId(),"name"=>$product->getName(), "sku"=>$product->getSku(),"UPC"=>$product->getUpc(),"QTY"=>$qtyStock, "Status"=>$product->getStatus(),"Location_16_Inventory"=>$inv16,"Location_33_Inventory"=>$inv33,"Total_Inventory"=>$invtotal);		
		}
	}
	
	 $filename = "Magento_Active_Products_inventory_report.xls";
	  $content = '';
	  header("Content-Disposition: attachment; filename=\"$filename\"");
	  header("Content-Type: text/csv");
	
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
	
	  
/*	
	file_put_contents($filename, $content);
	
	$from = "Inventory Report <inventoryReport@icuracao.com>"; 
    $subject = date("d.M H:i")." Dailly Inventory Report"; 
    $message = date("Y.m.d H:i:s")."\n Please find attached file with today's Inventory Report";
    $headers = "From: $from";
 
    // boundary 
    $semi_rand = md5(time()); 
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
 	
	$files = array('Magento_items.xls');
	
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
    $ok = @mail('Chrissie.Lavender@icuracao.com,Steve.Garcia@icuracao.com,julian.munoz@icuracao.com', $subject, $message, $headers, $returnpath); 
	 if($ok){ echo 'Sent'; } else { echo 'not sent'; }*/