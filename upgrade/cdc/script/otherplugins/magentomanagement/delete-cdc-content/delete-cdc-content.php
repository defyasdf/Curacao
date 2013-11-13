<?php
	ini_set('display_errors',1);
	error_reporting(E_ALL);
include("../../dbw.php");
$getco = mysql_query("select * from direct_cdc_delete_content") or die(mysql_error());
while($getcorow = mysql_fetch_object($getco))
{
echo $sku = trim($getcorow->SKU);
echo "<br>";
mysql_query("DELETE from finalproductlist WHERE product_sku='$sku'") or die(mysql_error());
mysql_query("DELETE from masterproducttable WHERE product_sku='$sku'") or die(mysql_error());
mysql_query("DELETE from spenishdata WHERE product_sku='$sku'") or die(mysql_error());
}
?>