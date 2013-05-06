<?php 

	error_reporting(E_ALL | E_STRICT);
	
	ini_set('max_execution_time', 0);

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
	ini_set('display_errors', 1);
	umask(0);
	Mage::app('default'); 

		$fname		= 'Sanjay';
		$lname		= 'Prajapati';
		$email		= 'sanjau.pra@gmail.com';
		$street		= '5826 Brentwood Pl';
		$street2	= '';
		$city		= 'Fontana';
		$region		= '';
		$postcode	= '90015';
		$country_id = 'US';
		$pass       = 'snprajapati';
		$curacao_id = '';
		$tel        = '3234856587';
		$fax        = '';
		
		$customer = Mage::getModel('customer/customer');
		$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
		$customer->loadByEmail($email);
		//
		// Check if the email exist on the system.
		// If YES,  it will not create a user account. 
		//		
		if(!$customer->getId()) {
		   //setting data such as email, firstname, lastname, and password 
		
			$customer->setEmail($email); 
			$customer->setFirstname($fname);
			$customer->setLastname($lname);
			$customer->setPasswordHash($pass);
			$customer->setCuracaocustid($curacao_id);
			
			$address = Mage::getModel('customer/address');
			$address->setStreet($street)
					->setFirstname($fname)
					->setLastname($lname)
					->setCity($city)
					->setCountry_id($country_id)
					->setRegion($region)
					->setPostcode($postcode)
					->setTelephone($tel)
					->setFax($fax)
					->setIsDefaultBilling('1')
					->setIsDefaultShipping('1');
					
			$customer->addAddress($address);
	
		  
		}
		
		try{
		  //the save the data and send the new account email.
		  $customer->save();
		  $customer->setConfirmation(null);
		  $customer->save(); 
		  //$customer->sendNewAccountEmail();
		  
		 $q = 'update customers set status = 1 where customers_id = '.$row['customers_id'];		  
		 mysql_query($q);
		  
		}
		
		catch(Exception $ex){
		 
		}

	 echo 'Customers has been imported successfully';