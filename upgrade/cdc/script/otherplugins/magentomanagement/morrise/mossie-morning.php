<?php
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set("memory_limit","2048M");
$stocknum = 0;
// first db connection start
$connect1 = mysql_connect("192.168.100.121","curacaodata","curacaodata");
mysql_select_db("cdc",$connect1);
// first db connection ends
// second db connection start
$connect2 = mysql_connect("192.168.100.121","curacaodata","curacaodata");
mysql_select_db("curacao_magento",$connect2);
// second db connection ends

// include('Mfunctions.php');
/* $inventryfile_links = array (
						'1'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_001.xml",
						'2'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_002.xml",
						'3'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_003.xml",
						'4'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_004.xml",
						'5'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_005.xml",
						'6'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_006.xml",
						'7'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_007.xml",
						'8'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_008.xml",
						'9'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_009.xml",
						'10'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_010.xml",
						'11'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_011.xml",
						'12'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_012.xml",
						'13'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_013.xml",
						'14'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_014.xml",
						'15'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_015.xml",
						'16'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_016.xml",
						'17'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_017.xml",
						'18'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_018.xml",
						'19'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_019.xml",
						'20'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_020.xml",
						'21'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_021.xml",
						'22'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_022.xml",
						'23'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_023.xml",
						'24'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_024.xml",
						'25'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_025.xml",
						'26'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_026.xml",
						'27'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_027.xml",
						'28'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_028.xml",
						'29'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_029.xml",
						'30'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_030.xml",
						'31'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_031.xml",
						'32'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_032.xml",
						'33'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_033.xml",
						'34'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_034.xml",
						'35'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_035.xml",
						'36'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_036.xml",
						'37'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_037.xml",
						'38'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_038.xml",
						'39'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_039.xml",
						'40'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_040.xml",
						'41'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_041.xml",
						'42'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_042.xml",
						'43'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_043.xml",
						'44'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_044.xml",
						'45'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_045.xml",
						'46'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_046.xml",
						'47'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_047.xml",
						'48'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_048.xml",
						'49'=>"http://morris.morriscostumes.com/out/190314/available_batchyynyy_049.xml"
							); */

$inventryfile_links = array (
						'1'=>"http://morris.morriscostumes.com/out/available_batchnynyy_001.xml",
						'2'=>"http://morris.morriscostumes.com/out/available_batchnynyy_002.xml",
						'3'=>"http://morris.morriscostumes.com/out/available_batchnynyy_003.xml",
						'4'=>"http://morris.morriscostumes.com/out/available_batchnynyy_004.xml",
						'5'=>"http://morris.morriscostumes.com/out/available_batchnynyy_005.xml",
						'6'=>"http://morris.morriscostumes.com/out/available_batchnynyy_006.xml",
						'7'=>"http://morris.morriscostumes.com/out/available_batchnynyy_007.xml",
						'8'=>"http://morris.morriscostumes.com/out/available_batchnynyy_008.xml",
						'9'=>"http://morris.morriscostumes.com/out/available_batchnynyy_009.xml",
						'10'=>"http://morris.morriscostumes.com/out/available_batchnynyy_010.xml",
						'11'=>"http://morris.morriscostumes.com/out/available_batchnynyy_011.xml",
						'12'=>"http://morris.morriscostumes.com/out/available_batchnynyy_012.xml",
						'13'=>"http://morris.morriscostumes.com/out/available_batchnynyy_013.xml",
						'14'=>"http://morris.morriscostumes.com/out/available_batchnynyy_014.xml",
						'15'=>"http://morris.morriscostumes.com/out/available_batchnynyy_015.xml",
						'16'=>"http://morris.morriscostumes.com/out/available_batchnynyy_016.xml",
						'17'=>"http://morris.morriscostumes.com/out/available_batchnynyy_017.xml",
						'18'=>"http://morris.morriscostumes.com/out/available_batchnynyy_018.xml",
						'19'=>"http://morris.morriscostumes.com/out/available_batchnynyy_019.xml",
						'20'=>"http://morris.morriscostumes.com/out/available_batchnynyy_020.xml",
						'21'=>"http://morris.morriscostumes.com/out/available_batchnynyy_021.xml",
						'22'=>"http://morris.morriscostumes.com/out/available_batchnynyy_022.xml",
						'23'=>"http://morris.morriscostumes.com/out/available_batchnynyy_023.xml",
						'24'=>"http://morris.morriscostumes.com/out/available_batchnynyy_024.xml",
						'25'=>"http://morris.morriscostumes.com/out/available_batchnynyy_025.xml",
						'26'=>"http://morris.morriscostumes.com/out/available_batchnynyy_026.xml",
						'27'=>"http://morris.morriscostumes.com/out/available_batchnynyy_027.xml",
						'28'=>"http://morris.morriscostumes.com/out/available_batchnynyy_028.xml",
						'29'=>"http://morris.morriscostumes.com/out/available_batchnynyy_029.xml",
						'30'=>"http://morris.morriscostumes.com/out/available_batchnynyy_030.xml",
						'31'=>"http://morris.morriscostumes.com/out/available_batchnynyy_031.xml",
						'32'=>"http://morris.morriscostumes.com/out/available_batchnynyy_032.xml",
						'33'=>"http://morris.morriscostumes.com/out/available_batchnynyy_033.xml",
						'34'=>"http://morris.morriscostumes.com/out/available_batchnynyy_034.xml",
						'35'=>"http://morris.morriscostumes.com/out/available_batchnynyy_035.xml",
						'36'=>"http://morris.morriscostumes.com/out/available_batchnynyy_036.xml",
						'37'=>"http://morris.morriscostumes.com/out/available_batchnynyy_037.xml",
						'38'=>"http://morris.morriscostumes.com/out/available_batchnynyy_038.xml",
						'39'=>"http://morris.morriscostumes.com/out/available_batchnynyy_039.xml",
						'40'=>"http://morris.morriscostumes.com/out/available_batchnynyy_040.xml",
						'41'=>"http://morris.morriscostumes.com/out/available_batchnynyy_041.xml",
						'42'=>"http://morris.morriscostumes.com/out/available_batchnynyy_042.xml",
						'43'=>"http://morris.morriscostumes.com/out/available_batchnynyy_043.xml",
						'44'=>"http://morris.morriscostumes.com/out/available_batchnynyy_044.xml",
						'45'=>"http://morris.morriscostumes.com/out/available_batchnynyy_045.xml",
						'46'=>"http://morris.morriscostumes.com/out/available_batchnynyy_046.xml",
						'47'=>"http://morris.morriscostumes.com/out/available_batchnynyy_047.xml",
						'48'=>"http://morris.morriscostumes.com/out/available_batchnynyy_048.xml",
						'49'=>"http://morris.morriscostumes.com/out/available_batchnynyy_049.xml"
							);

for($i=1;$i<26;$i++){
	$xml=simplexml_load_file("$inventryfile_links[$i]");
	$xmlsize = sizeof($xml);
	$inventrydata = $xml->Available;

	foreach($inventrydata as $product){
		// $data[] = array("Part Number"=>$product->Part,"QTY"=>$product->QTY);
		$productmagid = get_product_mosse_id($product->Part);
		if($productmagid){
			if($product->Qty > 0)
			{
				$stockstatus = 1;
			}
			else
			{
				$stocknum = $stocknum+1;
				$stockstatus = 0;
			}
			if($productmagid){
				$database = mysql_query("UPDATE curacao_magento.cataloginventory_stock_item item_stock, curacao_magento.cataloginventory_stock_status status_stock  SET item_stock.qty = '$product->Qty', item_stock.is_in_stock = $stockstatus, status_stock.qty = '$product->Qty', status_stock.stock_status = $stockstatus WHERE item_stock.product_id = '$productmagid' AND item_stock.product_id = status_stock.product_id ") or die(mysql_error());
			echo "-".$product->Part."-".$product->Qty;

}
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
echo "numberof thems set to zero ".$stocknum;
?>