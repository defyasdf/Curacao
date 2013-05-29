<?php
	

class Excellence_Pay_Model_Pay extends Mage_Payment_Model_Method_Abstract
{
   protected $_code = 'pay';
   protected $_formBlockType = 'pay/form_pay';
 
    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
		}
		
		$ccv = $data->getCcv();
		if(trim($ccv) == ''){
			Mage::throwException('CCV Is required');
			//return;
		}
		if(Mage::getSingleton('core/session')->getCheckouttrackid()){
			
			$server = '192.168.100.121';
			$user = 'curacaodata';
			$pass = 'curacaodata';
			$db = 'icuracaoproduct';
			
			$link = mysql_connect($server,$user,$pass);
			
			mysql_select_db($db,$link);
					
			$query = 'select place_order from checkouttrack where checkouttrackid = "'.Mage::getSingleton('core/session')->getCheckouttrackid().'"';
			$re = mysql_query($query);
			$row = mysql_fetch_array($re);
			$pl_order = (int)$row['place_order']+1;
			$sql = 'update checkouttrack set place_order = "'.$pl_order.'" where checkouttrackid = "'.Mage::getSingleton('core/session')->getCheckouttrackid().'"';
			mysql_query($sql);
			mysql_close($link);
		}	
			
		$Data = Mage::getSingleton('core/session')->getCuracacaodp();
		if($Data){
			Mage::getSingleton('core/session')->unsCuracacaodp();
		}
		if(Mage::getSingleton('core/session')->getAuthentication()){
			Mage::getSingleton('core/session')->unsAuthentication();
		}
		if(Mage::getSingleton('core/session')->getAuthenticationerror()){
			Mage::getSingleton('core/session')->unsAuthenticationerror();
		}
		Mage::getSingleton('core/session')->setCuracacaonum($data->getCheckNo());
		$session = Mage::getSingleton('customer/session', array('name'=>'frontend')); 
		$customer_data = Mage::getModel('customer/customer')->load($session->id);	
		if($customer_data->lockattempt){
			Mage::getSingleton('core/session')->setAuthentication(0);
			Mage::getSingleton('core/session')->setAuthenticationerror("acc_lock");
			Mage::getSingleton('core/session')->unsAttempt();
			Mage::throwException('Account has been locked due to over 3 attempts'); 		
		}		
		if(Mage::getSingleton('core/session')->getAttempt()>6){
			$customer_data->lockattempt = '1';
			$customer_data->save();
			Mage::getSingleton('core/session')->setAuthentication(0);
			Mage::getSingleton('core/session')->setAuthenticationerror("acc_lock");
			Mage::getSingleton('core/session')->unsAttempt();
			Mage::throwException('Account has been locked due to over 3 attempts'); 
								
		}
		if(Mage::getSingleton('core/session')->getAttempt()){
			$count = Mage::getSingleton('core/session')->getAttempt();
			Mage::getSingleton('core/session')->setAttempt($count+1);
		}else{
			Mage::getSingleton('core/session')->setAttempt(1);
		}
		
		$no = $data->getCheckNo();
		if($no=='5864577'){
			$ccv = '';
		}
		if(is_numeric($no)){
		if(substr(trim($no),0,1)!='5'){
			$no = '5'.substr($no,1);	
		}
		}else{
			Mage::getSingleton('core/session')->setAuthenticationerror("acc_no_error");
			Mage::throwException('Please input valid number for the account');
			
		}
		if($data->getCc_dob_year()){
			$dob = $data->getCc_dob_year().'-'.$data->getCc_dob_month().'-'.$data->getCc_dob_day();
		}
		$ssn = $data->getSsn();
		$maiden = $data->getMaiden();
		
		
		if(trim($no)!=''){
			if(substr(trim($no),0,1)!='5'){
				Mage::getSingleton('core/session')->setAuthentication(0);
				Mage::getSingleton('core/session')->setAuthenticationerror("acc_error");
				Mage::throwException('Accpunt not active only raference number'); 			
			}
			$d = $this->validate_customer_calculate_dp($no, $dob, $ssn, $maiden, $ccv);
			if($d!=0){
				Mage::getSingleton('core/session')->setCuracacaodp($d);
				Mage::throwException('Initial Down Payment Require $'.$d); 			
			}		
		}else{
			Mage::throwException('Curacao Credit'); 		
		}
		
		
				
        return $this;
    }
	
	public function validate_customer_calculate_dp($cust_num,$dob,$ssn,$maiden,$ccv){
	  
	  	$cust_num = str_replace('-','',str_replace(' ','',$cust_num));
	 	$quote = Mage::getModel('checkout/cart')->getQuote();
		 // Authentication, Authorization and Downpayment calculation
		$amount = $quote->getGrandTotal();
		
		
		//echo Mage::getSingleton('adminhtml/session_quote')->getQuote()->getBillingAddress()->getPostcode();
		//exit;
		
		if(!$amount){
			$quote = Mage::getSingleton('adminhtml/session_quote')->getQuote();
			$amount = $quote->getGrandTotal();
		}
		
		$session = Mage::getSingleton('customer/session', array('name'=>'frontend')); 
		$customer_data = Mage::getModel('customer/customer')->load($session->id);
		
		// Originally this
		
		
	/*	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
		$ns = 'http://lacuracao.com/WebServices/eCommerce/';
		
		$headerbody = array('UserName' => 'mike', 
							'Password' => 'ecom12'); 
		//Create Soap Header.        
		$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
				
		//set the Headers of Soap Client. 
		$h = $proxy->__setSoapHeaders($header); 
		/*$arr = array(
											'CustID' => $cust_num,
											'DOB' => $dob,
											'SSN' => $ssn,
											'MMaiden' => strtoupper($maiden),
											'Amount' => $amount,
											'CCV' => $ccv
											);
		print_r($arr);
		exit;
		
		$credit = $proxy->ValidateDP(array(
											'CustID' => $cust_num,
											'DOB' => $dob,
											'SSN' => $ssn,
											'MMaiden' => strtoupper($maiden),
											'Amount' => $amount,
											'CCV' => $ccv
											),
										 "http://www.lacuracao.com/LAC-eComm-WebServices", 
										 "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
										 false, null , 'rpc', 'literal');  
		
		
		$result =  $credit->ValidateDPResult;*/
		//echo '<pre>';
		//	print_r($result);	
		//echo '</pre>';
		
		// End Original
		
		// New change
		
			$url = 'http://108.171.160.207/SOAP/authenticate_user.php';
			$fields = array(	
								'CustID' => $cust_num,
								'DOB' => $dob,
								'SSN' => $ssn,
								'MMaiden' => strtoupper($maiden),
								'Amount' => $amount,
								'CCV' => $ccv
							);
			$fields_string = '';
			//url-ify the data for the POST
			foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			rtrim($fields_string, '&');
			
			//open connection
			$ch = curl_init();
			
			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL, $url);
			curl_setopt($ch,CURLOPT_POST, count($fields));
			curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			//execute post
			$result = curl_exec($ch);
			$result = unserialize($result);
		//	print_r(unserialize($result));
		
			//close connection
			curl_close($ch);
		
		
		// End New Change	
		
		
		//Adding new variables to quote
		
		$quote->setpayment_method("Curacao Credit");
		$quote->setar_response($result->StatusMessage);
		$quote->setdp_amount($result->DownPayment);
		if($result->DownPayment>0){
			$quote->setdp_required("1");
		}else{
			$quote->setdp_required("0");
		}
		
		$quote->save();
		
		$server = '192.168.100.121';
		$user = 'curacaodata';
		$pass = 'curacaodata';
		$db = 'icuracaoproduct';
		
		$link = mysql_connect($server,$user,$pass);
		
		mysql_select_db($db,$link);
		if(Mage::getSingleton('core/session')->getTrackingId()){
			$s = "update curacao_cust_tracking set cust_number = '".$cust_num."', ccv = '".$ccv."', cust_dob = '".$dob."', cust_ssn = '".$ssn."', cust_maiden = '".$maiden."', cust_amount = '".$amount."', ar_response = '".$result->StatusMessage."', payattempt = '".Mage::getSingleton('core/session')->getAttempt()."' where trackId = ".Mage::getSingleton('core/session')->getTrackingId();
			mysql_query($s,$link);
			$tracker_id = Mage::getSingleton('core/session')->getTrackingId();
		}else{
			$sql = "INSERT INTO `curacao_cust_tracking` (`cust_number`,ccv, `cust_dob`, `cust_ssn`, `cust_maiden`, `cust_amount`, `ar_response`, `order_complete`, checkoutdate) VALUES ('".$cust_num."','".$ccv."', '".$dob."', '".$ssn."', '".$maiden."', '".$amount."', '".$result->StatusMessage."', '0', '".date('Y-m-d')."')";
			mysql_query($sql);
			$tracker_id = mysql_insert_id();
			Mage::getSingleton('core/session')->setTrackingId($tracker_id);		
		}
		if(strtolower($result->StatusMessage) == 'ok'){
			
			if(!Mage::getSingleton('core/session')->getCustbalance()){
				$balance = file_get_contents('http://108.171.160.207/SOAP/getbalance.php?custnum='.$cust_num);
				Mage::getSingleton('core/session')->setCustbalance($balance);
			}
			
			Mage::getSingleton('core/session')->setCuracacaonum($cust_num);
			if($session->id){
				$customer_data->isauthenticate = '1';
				$customer_data->curacaocustid = $cust_num;
				$customer_data->save();
			}
			$dp = $result->DownPayment;
			if($dp>0){
				$que = "update curacao_cust_tracking set downpayment_req = 1, downpayment = '".$dp."' where trackId = ".$tracker_id;
				mysql_query($que);
			}
			Mage::getSingleton('core/session')->setTrackingId($tracker_id);
		}else{
			$dp = $amount;
			$response = explode('[',$result->StatusMessage);
			if(strtolower($response[0]) == 'authentication error'){
				$auth = str_replace(']','',$response[1]);
				
			}else{
				$auth = 'credit_error';
			}
			Mage::getSingleton('core/session')->setAuthenticationerror($auth);
			Mage::getSingleton('core/session')->setAuthentication(0);
			
			if(strtolower($result->StatusMessage) == 'authentication error'){
				Mage::throwException('Authentication Failed');
			}else{
				Mage::throwException('Credit Related issue Please contact credit : '.$result->StatusMessage);
			}
			
		}	
		$sql_query = "update userActivitytrack set payattempt = '1', ar_response = '".$result->StatusMessage."' where uaId = ".Mage::getSingleton('core/session')->getTrackid();
		mysql_query($sql_query);
		
		mysql_close($link);
		/*if($cust_num=='53145246'){
			$dp = 0;
		}else{
			$grandTotal = Mage::getModel('checkout/cart')->getQuote()->getGrandTotal();
			$dp = $grandTotal - 10;
		}	*/
		return $dp;
	}
}
?>
