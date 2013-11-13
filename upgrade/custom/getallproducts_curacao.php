<?php
	require_once('./_includes/ini_settings.php');
	require_once('./_includes/db_config.php');
	require_once('./_includes/mage_head.php');

	Mage::app('default');	
	$currentStore = Mage::app()->getStore()->getId();
	Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);	
	
	$collection = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*') // select all attributes
		->addAttributeToFilter('vendorid', '2139')
		->setCurPage(4); // set the offset (useful for pagination)
		
	$j = 1;
	
	foreach ($collection as $product) {
		$cat_ids = Mage::getResourceSingleton('catalog/product')->getCategoryIds($product);
		$cat = implode('_',$cat_ids);
		$qtyStock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product)->getQty();
		$data[] = array( "product_id"=>$product->getId(),"name"=>$product->getName(), "sku"=>$product->getSku(),"category_tree"=>$cat,"QTY"=>$qtyStock, "price"=>$product->getPrice(), "Special_price"=>$product->getSpecialPrice(), "Cost_price"=>$product->getCost(),"shipping"=>$product->getShprate(),"Status"=>$product->getStatus());
		$j++;
	}
	
	
	$filename = "Magento_Curacao_Products.xls";
	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel");
	
	$flag = false;
	foreach($data as $row) {
	
		if(!$flag) {
			// display field/column names as first row
			echo implode("\t", array_keys($row)) . "\r\n";
			$flag = true;
		}
		// array_walk($row, 'cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	}
	
	mysql_close($link);
	
	exit;
