<?php
	ini_set('default_socket_timeout',120);
	/*$proxy = new SoapClient('http://www.icuracao.com/api/?wsdl');
	$sessionId = $proxy->login('curacao', 'sanjaycuracao');

	$result = $proxy->call($sessionId, 'unlock_customer.unlockCustomer', '5864577');
	echo $result;*/
	
	$proxy = new SoapClient('http://www.icuracao.com/api/v2_soap/?wsdl'); // TODO : change url
	$sessionId = $proxy->login('curacao', 'sanjaycuracao'); // TODO : change login and pwd if necessary
	
	$result = $proxy->salesOrderInfo($sessionId, '100004933');
	echo '<pre>';
		print_r($result);
	echo '</pre>';
//	var_dump($result);
?>