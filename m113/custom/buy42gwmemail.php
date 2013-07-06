<?php	

// INI Setting	
    ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
// End INI Setting	
// Server DB setting
	$server = '192.168.100.121';
	$user = 'curacaodata';
	$pass = 'curacaodata';
	$db = 'curacao_magento';
	$link = mysql_connect($server,$user,$pass);
	$link1 = mysql_connect($server,$user,$pass,true);
	mysql_select_db($db,$link) or die("No DB");	
	mysql_select_db('icuracaoproduct',$link1) or die("No DB");
// End Server DB settings
// Mage Class setting	
	$mageFilename = '/var/www/html/app/Mage.php';	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	umask(0);
	Mage::app('default'); 
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
// End Mage class setting	
// Checking the 	
	$sql = "select * from addbuy42email where sendpromotion = 0";
	$re = mysql_query($sql,$link1);
	while($row = mysql_fetch_array($re)){

		$query = "select * from sales_flat_order where customer_email = '".$row['email']."' and created_at >= '".$row['created_date']."'";
		$result = mysql_query($query,$link) or die(mysql_error());
		if(mysql_num_rows($result)>0){
			while($orow = mysql_fetch_array($result)){
				if($orow['status'] == 'complete'){
					$order = Mage::getModel('sales/order')->load($orow['entity_id']);
					$items = $order->getAllItems();
					foreach ($items as $itemId => $item){
						$product = Mage::getModel('catalog/product');
						$product->load($item->getProductId());	
						if($product->gettv_screen_size()){
							$send = 0;
							if($product->gettv_screen_size() == '16' || $product->gettv_screen_size() == '15' || $product->gettv_screen_size() == '154'){
								//echo 'I am sending bronto an email update';
								$q = 'update addbuy42email set sendpromotion = 1 where doeid = '.$row['doeid'];
								if(mysql_query($q)){
									$send = 1;							
									$email = $row['email'];
								}
								break;
							}
						}
					}
				}
			}
			
		}
	}	

	if($send == 1){
	
	$coupon = file_get_contents('http://m113.icuracao.com/onestepcheckout/ajax/createbuy42coupon/');
	
	$code = json_decode($coupon);
	
	?>
	<img src="http://app.bronto.com/public/?q=direct_add&fn=Public_DirectAddForm&id=acxhzmypejmnhsowqaqxwyyhyesgbcd&email=<?php echo $email;?>&field1=firstname,set,Sanjay&field2=lastname,set,Prajapati&field3=Registered_in_Magento,set,True&field4=GWM_TV_Promo,set,<?php echo $code->code;?>&list5=0bc603ec00000000000000000000000540e4" width="0" height="0" border="0" alt=""/>
	<?php
	}
	