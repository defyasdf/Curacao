<?php

// For replacing the SKU we pass two sku in one argument saperated by : so first one is old (current sku) : second one is new sku

// Format: OLDSKU:NEWSKU
        $proxy = new SoapClient('http://www.icuracao.com/api/?wsdl');
        $sessionId = $proxy->login('curacao', 'sanjaycuracao');

        $result = $proxy->call($sessionId, 'replace_sku.replaceSku', '07S-C37-MATRIX2001:07S-C37-MATRIX2000');
       echo $result;
	exit;

//  For Unloacking the customer we need customer number.


        $proxy = new SoapClient('http://www.icuracao.com/api/?wsdl');
        $sessionId = $proxy->login('username', 'apikey');

        $result = $proxy->call($sessionId, 'unlock_customer.unlockCustomer', '5864577');
        echo $result;
?>