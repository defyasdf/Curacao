<?php

error_reporting(E_ALL | E_STRICT);
	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	ini_set('display_errors', 1);
	umask(0);
	Mage::app('default'); 
	
	
	

	$handle = fopen(Mage::getBaseUrl()."/media/import/users.csv", "r");

    $i=0;
    while (($element = fgetcsv($handle, 5000, ";")) !== FALSE) {
        if($i==1){
            var_dump($element);
            $row['ID'] = utf8_encode($element[1]);
            $row['name'] = utf8_encode($element[2]);
            $row['street'] = utf8_encode($element[3]);
            $row['city'] = utf8_encode($element[4]);
            $row['zipcode'] = utf8_encode($element[5]);
            $row['region_code'] = array(/*INSERT STATE CODE HERE*/);
            $row['region'] = $row['region_code'][utf8_encode($element[6])];
            $row['phone'] = utf8_encode($element[7]);
            $row['fax'] = utf8_encode($element[8]);

            $data['IDUser'] = $this->createCustomer($row);
            exit;
        }
        $i++;
    }
    
	
?>