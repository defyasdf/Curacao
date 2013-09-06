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
			
	$email = $_REQUEST['email'];
	
	// THE "EASY" WAY (but sends a confirmation email to the customer
	//$subscriber = Mage::getModel('newsletter/subscriber')->subscribe($email);
	
	
	
		$subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
			
		if (!$subscriber->getId()) {
		 	
			$subscriber->setStatus(Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED);
		   	$subscriber->setSubscriberEmail($email);
			//$subscriber->setSubscriberConfirmCode($subscriber->RandomSequence());
			$subscriber->setStoreId(Mage::app()->getStore()->getId());
			try {
				$subscriber->save();
			}
			catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}else{
			echo 'Already Exist';
		}
		
