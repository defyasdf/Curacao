<?php 

include 'includes/config.php';

$sql = "INSERT INTO `masterproducttable` (`prduct_name`, `product_description`, `product_sku`, `product_upc`, `product_msrp`, `product_map`, `product_brand`, `product_img_path`, `product_features`, `product_source`, `product_specs`) VALUES ('".htmlspecialchars($_POST['pname'],ENT_QUOTES)."', '".htmlspecialchars($_POST['pDesc'],ENT_QUOTES)."', '".$_POST['psku']."', '".$_POST['pupc']."', '".$_POST['pmsrp']."', '".$_POST['pmap']."', '".$_POST['pbrand']."', '".$_POST['pimg']."', '".htmlspecialchars($_POST['pFeature'],ENT_QUOTES)."', '3512', '".htmlspecialchars($_POST['pSpecs'],ENT_QUOTES)."')";

if(mysql_query($sql)){
	echo 1;
}else{
	echo 0;	
}