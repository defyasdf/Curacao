<?php

	$proxy = new SoapClient('http://data.icuracao.com/api/soap/?wsdl');
	$sessionId = $proxy->login('snprajapati', 'sanjay123');
	
	$link = mysql_connect('192.168.100.121','curacaodata','curacaodata');
	mysql_select_db('icuracaoproduct');
	//var_dump($proxy->__getLastRequest());
	$sql = "SELECT * FROM `finalproductlist` WHERE `magento_category_id` !=0 AND `magento_product_id` !=0 AND `inmagento` =0 limit 0,10";
	$resu = mysql_query($sql);
	while($row = mysql_fetch_array($resu)){
		print_r(array($row['magento_category_id'], $row['magento_product_id']));
				
		$proxy->call($sessionId, 'category.assignProduct', array($row['magento_category_id'], $row['magento_product_id']));
		
		$q = 'update finalproductlist set `inmagento` = 1 where magento_product_id = '.$row['magento_product_id'];
		if(mysql_query($q)){
		
			echo $row['magento_product_id'].' : Added';
		}else{
			echo $row['magento_product_id'].' : Not Added';
		}

	}