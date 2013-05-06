<?php
ini_set('max_execution_time', 0);
//DB settings
$server = '192.168.100.121';
$user = 'curacaodata';
$pass = 'curacaodata';
$db = 'icuracaoproduct';
$link = mysql_connect($server,$user,$pass);
mysql_select_db($db,$link);	

$mageFilename = '/var/www/html/app/Mage.php';
require_once $mageFilename;
Varien_Profiler::enable();
Mage::setIsDeveloperMode(true);
umask(0);
Mage::app('default'); 
//Getting current store ID	
$currentStore = Mage::app()->getStore()->getId();
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
$ns = 'http://lacuracao.com/WebServices/eCommerce/';

//set the headers values

$headerbody = array('UserName' => 'mike', 
                    'Password' => 'ecom12'); 
//Create Soap Header.        
$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        

//set the Headers of Soap Client. 
$h = $proxy->__setSoapHeaders($header); 

$sql = 'select * from preapproved where pending = 1 and pendingToApprove != 1';
$re = mysql_query($sql);
while($row = mysql_fetch_array($re)){
$cust_id = '5'.substr($row['accnumber'],1);
$credit = $proxy->IsCustomerActive(array('CustomerID'=>$cust_id));
$result = $credit->IsCustomerActiveResult;

	if(trim(strtolower($result)) == 'yes'){
		
		$ccredit = $proxy->EComAcctCredit(array('cCust_ID'=>$cust_id));

		$cresult = $ccredit->EComAcctCreditResult;
		
		$cust_info = explode('|',$cresult);

		$q = "update preapproved set pendingToApprove = '1', approvedcreditlimit = '".$cust_info[8]."', accnumber = '".$cust_id."' where paID = ".$row['paID'];		
		if(mysql_query($q)){
			echo 'Record '.$row['paID'].' Updated';
		}
		
	}
}
