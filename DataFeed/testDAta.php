<?php

$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
mysql_select_db('icuracaoproduct',$conn);

$q = 'select * from masterproducttable where product_sku = "F8100900"';
$r = mysql_query($q);
$row = mysql_fetch_array($r);

	$s = cleardata($row['product_description']);
			

$data[] = array("Product Name"=>$row['prduct_name'], "Product sku"=>$row['product_sku'], "Product Description"=>$s, "Product UPC"=>$row['product_upc'], "Product Brand"=>$row['product_brand']);

//echo $str;

echo '<pre>';
	print_r($data);
echo '</pre>';


function cleardata($str){
	
	$s = explode(' ',trim(str_replace('<br>','',str_replace('\r\n','',$str))));
	$d = array();
	for($i=0;$i<sizeof($s);$i++){
				if(trim($s[$i])==''){
					unset($s[$i]);
				}else{
					$d[] = trim($s[$i]);
				}
		}
	
//	$p = str_replace('nl','',$d[28]);
	
	
	$p = trim( preg_replace( '/\s+/', ' ', $d[28]));
	

	$str = '';
		foreach($s as $v){
			if(trim($v)!=''){
				$str .= trim( preg_replace( '/\s+/', ' ', $v)).' ';
			}
			
		//	echo '<p>'.trim($v).'</p>';
		}
		
		return $str;
}