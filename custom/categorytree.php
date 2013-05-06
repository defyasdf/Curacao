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
	$string = '';
	function getcattree($catId){
		global $string;
		 $m = Mage::getModel('catalog/category')
			->load($catId)
			->getParentCategory();
		
		//$array = array_push($array,$m->getId());	
		$string .= $m->getId()."_";		
		  if($m->getLevel()>2){
			getcattree($m->getId());
		  }
	
		return $string;
	}
	
	$tree = getcattree(8);
	
	print_r($tree);
?>