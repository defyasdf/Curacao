<?php
$installer = $this;

$installer->startSetup();

$table = $installer->getTable('authorizenetcim/profile');

$installer->getConnection()->addColumn($table, 'default_payment_id', "INT(11) DEFAULT NULL");

$installer->endSetup();