<?php
	ini_set('default_socket_timeout',120);
	$proxy = new SoapClient('http://www.icuracao.com/api/?wsdl');
	$sessionId = $proxy->login('curacao', 'sanjaycuracao');

	$result = $proxy->call($sessionId, 'unlock_customer.unlockCustomer', '5864577');
	echo $result;
?>