<?php
	error_reporting(E_ALL | E_STRICT);
	
	$mageFilename = '../app/Mage.php';
	
	require_once $mageFilename;
	Varien_Profiler::enable();
	Mage::setIsDeveloperMode(true);
	ini_set('display_errors', 1);
	umask(0);
	Mage::app('default'); 

	$db = Mage::getSingleton('core/resource')->getConnection('core_write');
//	$result = $db->query('TRUNCATE dataflow_batch_export;');
//	$result = $db->query('TRUNCATE dataflow_batch_import;');
	$result = $db->query('TRUNCATE log_customer;');
	$result = $db->query('TRUNCATE log_quote;');
	$result = $db->query('TRUNCATE log_summary;');
	$result = $db->query('TRUNCATE log_summary_type;');
	$result = $db->query('TRUNCATE log_url;');
	$result = $db->query('TRUNCATE log_url_info;');
	$result = $db->query('TRUNCATE log_visitor;');
	$result = $db->query('TRUNCATE log_visitor_info;');
	$result = $db->query('TRUNCATE log_visitor_online;');
//	$result = $db->query('TRUNCATE report_viewed_product_index;');
//	$result = $db->query('TRUNCATE report_compared_product_index;');
//	$result = $db->query('TRUNCATE report_event;');
//	$result = $db->query('TRUNCATE index_event;'); 

	echo('all logs truncated');

?>