<?php
	error_reporting(E_ALL | E_STRICT);

	ini_set('display_errors', 1);
	ini_set("memory_limit","1024M");
	ini_set('max_execution_time', 0);	
	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);


	umask(0);
	Mage::app('default'); 
	
	
	$catalog_rule = Mage::getModel('catalogrule/rule')->load(5166);
	 $catalog_rule_skus = $catalog_rule->getMatchingProductIds();

		$rule = Mage::getModel('salesrule/rule')->load(5166); 

        $conditions = $rule->getConditions()->asArray();
		
		print_r($conditions);
		
		echo $conditions['conditions'][0]['conditions'][0]['value'];
			
		
		if($rule->getIsActive()){
			$start_ts = strtotime($rule->getfrom_date());
			$end_ts = strtotime($rule->getto_date());
			$user_ts = strtotime(date('Y-m-d'));
		
			if((($user_ts >= $start_ts) && ($user_ts <= $end_ts))){		
				echo 'I am valid';
			}else{
				echo 'I am not';
			}
		}