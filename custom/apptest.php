<?php 
	ini_set('max_execution_time', 300);
	
	

	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	
	//
	
	$headerbody = array('UserName' => 'mike', 
						'Password' => 'ecom12'); 
	
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
			
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
	
	
	
$credit = $proxy->CreateEstimateRev(array('CustomerID'=>'53145246',
										  'CreateDate' => date('Y-m-d\Th:i:s'),
										  'CreateTime' => '5:21PM',
										  'WebReference' => '798456',
										  'SubTotal' => '29.99',
										  'TaxAmount' => '1.65',
										  'DestinationZip' => '92336',
										  'ShipCharge' => '10.00',
										  'ShipDescription' => 'UPS GROUND',
										  'Detail'=>array('TEstLine'=>array('ItemType'=>'CUR',
																			  'Item_ID' => '33A-O55-98178',
																			  'Item_Name' => 'Memorex Mini TravelDrive USB flash drive 4 GB',
																			  'Model' => 	'98178',
																			  'Qty' => '1',
																			  'Price' =>'19.99',
																			  'Cost' => '',
																			  'Taxable' => 'Y',
																			  'WebVendor' => '0')
																  )
										  
                                        ),
                                     "http://www.lacuracao.com/LAC-eComm-WebServices", 
                                     "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
                                     false, null , 'rpc', 'literal');  
	
//	$result = $credit->InventoryLevelResult;
	
//$values = explode('|',$result);
	$re = $credit->CreateEstimateRevResult;
	
	$final = explode("|",$re);
	
	echo '<pre>';
		print_r($final);
	echo '</pre>';
	
?>