<?php
class Ria_Pickup_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getDetails($postData,$storeIds){
		$webserviceURL = Mage::getStoreConfig('ria_pickup/pickup_webservice/webserviceurl');
		$userName = Mage::getStoreConfig('ria_pickup/pickup_webservice/username');
		$passWord = Mage::getStoreConfig('ria_pickup/pickup_webservice/password');
		//$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/eCommerce/Main.asmx?WSDL');
		$proxy = new SoapClient($webserviceURL);
		$ns = 'http://lacuracao.com/WebServices/eCommerce/';
		//set the headers values

		$headerbody = array('UserName' => $userName, 
							'Password' => $passWord); 

		//Create Soap Header.        
		$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
				
		//set the Headers of Soap Client. 
		$h = $proxy->__setSoapHeaders($header); 

		$sku = $postData['clicked_ele_sku'];
		$credit = $proxy->InventoryLevel(array('cItem_ID'=>$sku,'cLocations'=>$storeIds));
		$result = $credit->InventoryLevelResult;
		$s = explode("\\",$result);
		$tot = 0;
		$stores = array();
		for($i=0;$i<(sizeof($s)-1);$i++){
			$inv = explode("|",$s[$i]);
			if($inv[1]-2>=1){
				$tot += $inv[1]-2;
				$stores[] = $inv[0];
			}
		}
		$returnresult['sku'] = $sku;
		$returnresult['stores'] = $stores;
		return $returnresult;
	}
}
	 