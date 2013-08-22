<?php
ini_set('max_execution_time', 0);
//DB settings
/*$server = '192.168.100.121';
$user = 'curacaodata';
$pass = 'curacaodata';
$db = 'icuracaoproduct';
$link = mysql_connect($server,$user,$pass);
mysql_select_db($db,$link);	

*/
$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/test/asis/Main.asmx');
$ns = 'http://lacuracao.com/WebServices/eCommerce/';

//set the headers values

//$headerbody = array('UserName' => 'mike', 
  //                  'Password' => 'ecom12'); 

//Create Soap Header.        
//$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
        
//set the Headers of Soap Client. 
//$h = $proxy->__setSoapHeaders($header); 
echo 'hello';
exit;

$json = '{"order_id":"1411890","subtotal":"123.50","tax":"0.00","shipping_revenue":"16.95","shipping_cost":"16.02","shipping_method":"FedEx Home Delivery","discount":"0.00","surcharge":"0.00","total":"123.50","created_date":"2013-08-12 18:20:17","invoice_date":"2013-08-12 18:20:20","ship_date":"2013-08-19 17:10:15","destination_zip":"78589-2259","tracking":[{"tracking_number":"111590115059314","carrier":"FedEx","cost":"16.02","ship_date":"08\/19\/2013","method":"FedEx Home Delivery"}],"items":[{"id":"533491","sku":"T115S1100-66527","vendor_sku":"33I-878-T115S1100","name":"Toshiba Satellite T115-S1100 11.6\" 1.3GHz Celeron 743 2GB DDR3 250GB Notebook PC","weight_lb":"1.00","quantity":"1","cost":"430.00","line_cost":"430.00","price":"123.50","line_price":"123.50"}]}';


$jd = json_decode($json);

echo '<pre>';
	print_r($jd);
echo '</pre>';

for($i=0;$i<sizeof($jd->items);$i++){
		$sku = explode("-",$jd->items[$i]->vendor_sku);
		
			$list['TEstLine'][] = array('ItemType'=>'CUR',
									  'Item_ID' => $jd->items[$i]->vendor_sku,
									  'Item_Name' => $jd->items[$i]->name,
									  'Model' => 	$sku[2],
									  'ItemSet' => 	'N',
									  'Qty' => (int)$jd->items[$i]->quantity,
									  'Price' =>$jd->items[$i]->price,
									  'Cost' => '',
									  'Taxable' => 'Y',
									  'WebVendor' => '0' ) ;
		}
	
	
	$param = array('CustomerID'=>'100-9015',
				  'CreateDate' => $jd->created_date,
				  'CreateTime' => date('h:i A'),
				  'WebReference' => $jd->order_id,
				  'SubTotal' => $jd->subtotal,
				  'TaxAmount' => $jd->tax,
				  'DestinationZip' => $jd->destination_zip,
				  'ShipCharge' => $jd->shipping_revenue,
				  'ShipDescription' => $jd->shipping_method,
				  'Detail'=>$list
	);
echo '<hr>';	
echo '<hr>';	
	echo '<pre>';
	print_r($param);
echo '</pre>';

echo '<hr>';	
echo '<hr>';	
