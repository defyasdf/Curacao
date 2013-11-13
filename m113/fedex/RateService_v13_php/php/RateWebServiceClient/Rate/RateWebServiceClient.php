<?php

// Copyright 2009, FedEx Corporation. All rights reserved.
// Version 12.0.0
ini_set("soap.wsdl_cache_enabled", "0");

if($_REQUEST['weight'] > 60 || strtolower($_REQUEST['shiptype']) == 'domestic'){
	echo 'within 3-5 business days|99.99';
	exit;
}

require_once('../../library/fedex-common.php');

$newline = "<br />";
//The WSDL is not included with the sample code.
//Please include and reference in $path_to_wsdl variable.
$path_to_wsdl = "../RateService_v13/RateService_v13.wsdl";


 
$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information

$request['WebAuthenticationDetail'] = array(
	'UserCredential' =>array(
		'Key' => getProperty('key'), 
		'Password' => getProperty('password')
	)
); 
$request['ClientDetail'] = array(
	'AccountNumber' => getProperty('shipaccount'), 
	'MeterNumber' => getProperty('meter')
);
$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request v13 using PHP ***');
$request['Version'] = array(
	'ServiceId' => 'crs', 
	'Major' => '13', 
	'Intermediate' => '0', 
	'Minor' => '0'
);
$request['ReturnTransitAndCommit'] = true;
$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
$request['RequestedShipment']['ShipTimestamp'] = date('c');
$request['RequestedShipment']['ServiceType'] = 'GROUND_HOME_DELIVERY'; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
$request['RequestedShipment']['TotalInsuredValue']=array('Ammount'=>699,'Currency'=>'USD');
$request['RequestedShipment']['Shipper'] = addShipper();
$request['RequestedShipment']['Recipient'] = addRecipient();
$request['RequestedShipment']['ShippingChargesPayment'] = addShippingChargesPayment();
$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; 
$request['RequestedShipment']['PackageCount'] = '1';
$request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';
$request['RequestedShipment']['RequestedPackageLineItems'] = addPackageLineItem1();
try 
{
	if(setEndpoint('changeEndpoint'))
	{
		$newLocation = $client->__setLocation(setEndpoint('endpoint'));
	}
	
	$response = $client ->getRates($request);
     
    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
    {  	
    	$rateReply = $response -> RateReplyDetails;
    	$time = array("ONE_DAY"=>'1','TWO_DAYS'=>'2','THREE_DAYS'=>'3','FOUR_DAYS'=>'4',"FIVE_DAYS"=>'5','SIX_DAYS'=>'6',"SEVEN_DAYS"=>'7');
		$days = $time[$rateReply->CommitDetails->TransitTime]+3;
		if(date('l',strtotime('+'.$days.' days')) == 'Sunday'){
			$days = $days+1;
		}
		/*foreach($rateReply as $rates){
			echo '<pre>';
				print_r($rates);
			echo '</pre>';
			/*echo $rates->ServiceType.'=>$'.number_format($rates->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount*1.5,2,".",",").'<br>';
			$days = $time[$rates->CommitDetails->TransitTime]+3;
			echo $rates->CommitDetails->TransitTime.' : ' .date('l jS \of F Y',strtotime('+'.$days.' days')).'<br>';
		}*/
	    echo date('l jS \of F Y',strtotime('+'.$days.' days')).'|';
	    echo number_format($rateReply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount*1.5,2,".",",");
    }
    else
    {
        echo '0';
    } 
    
    writeToLog($client);    // Write to log file   

} catch (SoapFault $exception) {
   printFault($exception, $client);        
}

function addShipper(){
	$shipper = array(
		'Contact' => array(
			'PersonName' => 'Curacao',
			'CompanyName' => 'Curacao',
			'PhoneNumber' => '9012638716'),
		'Address' => array(
			'StreetLines' => array('4444 Ayers Ave'),
			'City' => 'Vernon',
			'StateOrProvinceCode' => 'CA',
			'PostalCode' => '90058',
			'CountryCode' => 'US')
	);
	return $shipper;
}
function addRecipient(){
	
	$geoData = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$_REQUEST['zip'].'&sensor=false');
	$data = json_decode($geoData);

	$recipient = array(
		'Contact' => array(
			'PersonName' => '',
			'CompanyName' => '',
			'PhoneNumber' => ''
		),
		'Address' => array(
			'StreetLines' => array(''),
			'City' => $data->results[0]->address_components[2]->long_name,
			'StateOrProvinceCode' => $data->results[0]->address_components[sizeof($data->results[0]->address_components)-2]->short_name,
			'PostalCode' => $_REQUEST['zip'],
			'CountryCode' => 'US',
			'Residential' => true)
	);
	
	
	return $recipient;	                                    
}
function addShippingChargesPayment(){
	$shippingChargesPayment = array(
		'PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
		'Payor' => array(
		//	'ResponsibleParty' => array(
			'AccountNumber' => getProperty('billaccount'),
			'CountryCode' => 'US'
		)
	);
	return $shippingChargesPayment;
}
function addLabelSpecification(){
	$labelSpecification = array(
		'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
		'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
		'LabelStockType' => 'PAPER_7X4.75');
	return $labelSpecification;
}
function addSpecialServices(){
	$specialServices = array(
		'SpecialServiceTypes' => array('COD'),
		'CodDetail' => array(
			'CodCollectionAmount' => array('Currency' => 'USD', 'Amount' => 150),
			'CollectionType' => 'ANY')// ANY, GUARANTEED_FUNDS
	);
	return $specialServices; 
}
function addPackageLineItem1(){
	$packageLineItem = array(
		'SequenceNumber'=>1,
		'GroupPackageCount'=>1,
		'Weight' => array(
			'Value' => round($_REQUEST['weight']),
			'Units' => 'LB'
		)
	);
	return $packageLineItem;
}

?>