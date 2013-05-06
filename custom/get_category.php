<?php

	ini_set('max_execution_time', 0);
	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	
	
	umask(0);
	Mage::app('admin'); 
	
	
	function get_categories(){
	
	$category = Mage::getModel('catalog/category'); 
	$tree = $category->getTreeModel(); 
	$tree->load();
	$ids = $tree->getCollection()->getAllIds(); 
	$arr = array();
	if ($ids){ 
	foreach ($ids as $id){ 
	$cat = Mage::getModel('catalog/category'); 
	$cat->load($id);
	$arr[$id] = $cat->getName();
	} 
	}
	
	return $arr;
	
	}

  
  $arr =  get_categories();
$data = array();
 	foreach($arr as $id=>$val){
		$data[] = array('id'=>$id,"Name"=>$val);
	}
	
	  $filename = "Categories.xls";
	
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
	
	
	exit;	
