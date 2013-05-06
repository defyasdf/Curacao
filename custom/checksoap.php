<?php
	$proxy = new SoapClient('http://www.icuracao.com/api/?wsdl');
	$sessionId = $proxy->login('arwebservice', 'curaca0m1s');

	$result = $proxy->call($sessionId, 'unlock_customer.unlockCustomer', '5864577');
	echo $result;
?>