<?php
$installer = $this;

$installer->startSetup();

$table = $installer->getTable('sales/quote_payment');

$installer->getConnection()->addColumn($table, 'authorizenetcim_customer_id', "INT(11) DEFAULT NULL");
$installer->getConnection()->addColumn($table, 'authorizenetcim_payment_id', "INT(11) DEFAULT NULL");

$table = $installer->getTable('sales/order_payment');

$installer->getConnection()->addColumn($table, 'authorizenetcim_customer_id', "INT(11) DEFAULT NULL");
$installer->getConnection()->addColumn($table, 'authorizenetcim_payment_id', "INT(11) DEFAULT NULL");

$installer->endSetup();