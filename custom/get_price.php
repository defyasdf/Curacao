<?php 

	ini_set('max_execution_time', 300);
	//Set the soap client
	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
	$ns = 'http://lacuracao.com/WebServices/eCommerce/';
	// Set headers
	$headerbody = array('UserName' => 'mike', 
						'Password' => 'ecom12'); 
	//Create Soap Header.        
	$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
			
	//set the Headers of Soap Client. 
	$h = $proxy->__setSoapHeaders($header); 
	
	/*$credit = $proxy->ECCreditLimit(array('Cust_ID'=>$_GET['custnum']));
	
	$result = $credit->ECCreditLimitResult;
	
	echo $result;
	*/
	
	$credit = $proxy->GetSkuPrice(array('Sku'=>$_GET['sku']));
	
	$result = $credit->GetSkuPriceResult;
	
	$price_info = explode('|',$result);
	


if($price_info[0]=='OK'){
	
	if($price_info[2]>0){
		$rebate_price = $price_info[1]-$price_info[3];
		if($rebate_price<$price_info[2]){
			$price = $rebate_price;
		}else{
			$price = $price_info[2];
		}
	}else{
		$price = $price_info[1]-$price_info[3];
	}
	
	echo $price;
	
}




?>