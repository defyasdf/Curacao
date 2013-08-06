<?php
	// INI setting
		ini_set('max_execution_time', 0);
		ini_set('display_errors', 1);
		ini_set("memory_limit","1024M");

	// DB settings
		$server = '192.168.100.121';
		$user = 'curacaodata';
		$pass = 'curacaodata';
		$db = 'curacao_magento';
		$link = mysql_connect($server,$user,$pass);
		mysql_select_db($db,$link) or die("No DB");	

	//Magento Class Setting		
		$mageFilename = '/var/www/html/app/Mage.php';
		require_once $mageFilename;
		Varien_Profiler::enable();
		Mage::setIsDeveloperMode(true);
		umask(0);
		Mage::app('default'); 
		$currentStore = Mage::app()->getStore()->getId();
		Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
	
	//Codding Logic
	
//	print_r($_REQUEST);
	$msg = '';
	$err = 0;
	$query = 'update catalog_category_product set position = "'.sizeof($_REQUEST['cat'][1]).'" where category_id = "'.$_REQUEST['catId'].'"';
	if(mysql_query($query)){
		for($i=0;$i<sizeof($_REQUEST['cat'][1]);$i++){
		echo $sql = 'update catalog_category_product set position = "'.$i.'" where category_id = "'.$_REQUEST['catId'].'" and product_id = "'.$_REQUEST['cat'][1][$i].'"';
			//echo '<br>';
			if(mysql_query($sql)){
				$msg = '1';
			}else{
				$msg = '0';
				$err ++;
			}
			//echo $msg."<br>";
		}
	}
	
	if($err == 0){
		echo '1';
	}