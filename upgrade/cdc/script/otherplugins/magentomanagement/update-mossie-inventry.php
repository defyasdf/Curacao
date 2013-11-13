<?php

ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set("memory_limit","2048M");

// include('../dbw.php');

// first db connection start
$connect1 = mysql_connect("192.168.100.121","curacaodata","curacaodata");
mysql_select_db("cdc",$connect1);
// first db connection ends
// second db connection start
$connect2 = mysql_connect("192.168.100.121","curacaodata","curacaodata");
mysql_select_db("curacao_dev",$connect2);
// second db connection ends

include('Mfunctions.php');
$inventryfile_links = array (
						'1'=>"http://morris.morriscostumes.com/out/available_batchnynyn_001.xml",
						'2'=>"http://morris.morriscostumes.com/out/available_batchnynyn_002.xml",
						'3'=>"http://morris.morriscostumes.com/out/available_batchnynyn_003.xml",
						'4'=>"http://morris.morriscostumes.com/out/available_batchnynyn_004.xml",
						'5'=>"http://morris.morriscostumes.com/out/available_batchnynyn_005.xml",
						'6'=>"http://morris.morriscostumes.com/out/available_batchnynyn_006.xml",
						'7'=>"http://morris.morriscostumes.com/out/available_batchnynyn_007.xml"
							);
for($i=1;$i<8;$i++)
{
echo $inventryfile_links[$i]." - ";
$xml=simplexml_load_file("$inventryfile_links[$i]");
$xmlsize = sizeof($xml);
$inventrydata = $xml->Available;
foreach($inventrydata as $product)
{
echo $product->Part;
echo " - ";
echo $product->Qty;
echo "<br>";
$productmagid = get_product_mosse_id($product->Part);
if($productmagid)
{
// echo $productmagid;
if($product->Qty > 0)
$stockstatus = 1;
else
$stockstatus = 0;
$database = mysql_query("UPDATE curacao_dev.cataloginventory_stock_item item_stock, curacao_dev.cataloginventory_stock_status status_stock  SET item_stock.qty = '$product->Qty', item_stock.is_in_stock = $stockstatus, status_stock.qty = '$product->Qty', status_stock.stock_status = $stockstatus WHERE item_stock.product_id = '$productmagid' AND item_stock.product_id = status_stock.product_id ") or die(mysql_error());

}
}
}


function get_product_mosse_id($productsku)
{
	$data = mysql_query("SELECT * FROM cdc.`direct_mosse_product_ids` WHERE `sku`='$productsku'");
	if(mysql_num_rows($data) > 0)
	{
	$dataextract = mysql_fetch_object($data);
	return $dataextract->product_id;
	}
	else
	{
	return FALSE;
	}
}

?>