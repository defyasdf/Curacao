<?php
	$proxy = new SoapClient('http://staging.icuracao.com/api/?wsdl');
	$sessionId = $proxy->login('curacao', 'sanjaycuracao');

	$result = $proxy->call($sessionId, 'drop_item.dropItem', '5864577');
	echo $result;
?>