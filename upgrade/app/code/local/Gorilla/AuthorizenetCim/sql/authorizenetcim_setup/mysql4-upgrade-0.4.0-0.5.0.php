<?php
$installer = $this;

$installer->startSetup();

$table = $installer->getTable('sales/payment_transaction');

$installer->getConnection()->addColumn($table, 'authorizenetcim_txn_type', "VARCHAR(100) DEFAULT NULL");

$installer->endSetup();