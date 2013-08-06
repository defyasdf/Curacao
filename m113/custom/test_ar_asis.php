<?php
ini_set('max_execution_time', 0);
//DB settings
/*$server = '192.168.100.121';
$user = 'curacaodata';
$pass = 'curacaodata';
$db = 'icuracaoproduct';
$link = mysql_connect($server,$user,$pass);
mysql_select_db($db,$link);	


$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/test/asis/Main.asmx');
$ns = 'http://lacuracao.com/WebServices/eCommerce/';

//set the headers values

$headerbody = array('UserName' => 'mike', 
                    'Password' => 'ecom12'); 

//Create Soap Header.        
$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
        
//set the Headers of Soap Client. 
$h = $proxy->__setSoapHeaders($header); 

*/
$json = '{"order_id":1331064,"subtotal":"129.99","tax":"0.00","shipping_revenue":"0.00","shipping_cost":"12.00","shipping_method":"eBay Shipment","discount":"0.00","surcharge":"0.00","total":"129.99","created_date":"2013-06-16 21:10:06","invoice_date":"2013-06-16 21:10:09","ship_date":"2013-07-22 16:09:45","destination_zip":"20607","tracking":[{"tracking_number":"ABC2222333AA","carrier":"UPS","cost":"12.00","ship_date":"07\/31\/2013","method":"Not Specified"}],"items":[{"id":"498994","sku":"9000001301-55109","name":"Beats Solo HD RED Edition On-Ear Headphones","weight_lb":"1.00","quantity":"1","cost":"132.53","line_cost":"132.53","price":"129.99","line_price":"129.99"}]}';


$jd = json_decode($json);

echo '<pre>';
	print_r($jd);
echo '</pre>';

for($i=0;$i<sizeof($jd->items);$i++){
			$list['TEstLine'][] = array('ItemType'=>'CUR',
									  'Item_ID' => $jd->items[$i]->sku,
									  'Item_Name' => $jd->items[$i]->name,
									  'Model' => 	$jd->items[$i]->sku,
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
