<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('listrak/session')} ADD `ips` VARCHAR( 1000 ) CHARACTER SET ascii COLLATE ascii_general_ci
");
 
try {
	Mage::getModel("listrak/log")->addMessage("1.0.0-1.0.1 upgrade");
}
catch(Exception $e) { }

$installer->endSetup();