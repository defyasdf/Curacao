<?php 
	ini_set('max_execution_time', 300);
	
	

	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/test/eCommerce/Main.asmx?WSDL');
	
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	
	//
	
	$headerbody = array('UserName' => 'mike', 
						'Password' => 'ecom12'); 
	
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
			
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
	
	//$credit = $proxy->WebCustomerApplication('sfakjlkame','sljfllkjl','l','3234789654','sanjay@gmail.com','1625 W. Olympic blvd', 'Los Angeles','CA','90015','3235595459','755-75-4587','e2212345689','DL', 'somename','company','8001543654','1965-06-15T12:00:00','2015-06-15T12:00:00','10','10','100000','CA','173.240.126.254','E','0','');
	
$credit = $proxy->CreateEstimateRev(array('CustomerID'=>'53144587',
										  'CreateDate' => date('Y-m-d\Th:i:s'),
										  'CreateTime' => '5:21PM',
										  'WebReference' => '123456',
										  'SubTotal' => '286.98',
										  'TaxAmount' => '23.68',
										  'DestinationZip' => '92336',
										  'ShipCharge' => '0',
										  'ShipDescription' => 'UPS GROUND',
										  'Detail'=>array('TEstLine'=>array('ItemType'=>'CUR',
																			  'Item_ID' => '33F-P23-ZL810/01DU',
																			  'Item_Name' => 'Z-Line Designs Claremont Desk',
																			  'Model' => 	'ZL810/01DU',
																			  'Qty' => '1',
																			  'Price' =>'136.99',
																			  'Cost' => '100',
																			  'Taxable' => 'Y',
																			  'WebVendor' => '0'
																  ),
																  'TEstLine'=>array('ItemType'=>'CUR',
																			  'Item_ID' => '32C-ID4-LT19A8',
																			  'Item_Name' => 'Mega LT19A8 LCD 19" Combo / 720p HDTV',
																			  'Model' => 	'LT19A8',
																			  'Qty' => '1',
																			  'Price' =>'149.99',
																			  'Cost' => '100',
																			  'Taxable' => 'Y',
																			  'WebVendor' => '0'
																  )
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