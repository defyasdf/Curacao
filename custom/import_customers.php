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

//	Mage::getModel('customer/address');

	$sql = 'select * from customers where customers_id != 149516';
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result)){
	
		$sql1 = 'select * from address_book where customers_id = '.$row['customers_id'];
		$result1 = mysql_query($sql1);
		$row1 = mysql_fetch_array($result1);
		
		$fname		= $row1['entry_firstname'];
		$lname		= $row1['entry_lastname'];
		$email		= $row['customers_email_address'];
		$street		= $row1['entry_street_address'];
		$street2	= $row1['entry_street_address2'];
		$city		= $row1['entry_city'];
		$region		= $row1['entry_state'];
		$postcode	= $row1['entry_postcode'];
		$country_id = 'US';
		$pass       = $row['customers_password'];
		$curacao_id = $row['customers_lac_id'];
		$tel        = $row['customers_telephone'];
		$fax        = $row['customers_fax'];
		
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
		
		
	}
	
	 echo 'Customers has been imported successfully';