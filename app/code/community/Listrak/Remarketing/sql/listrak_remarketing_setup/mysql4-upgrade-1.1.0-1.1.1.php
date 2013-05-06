<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('listrak/session')} ADD `pi_id` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL 
");
 
try {
	Mage::getModel("listrak/log")->addMessage("1.1.0-1.1.1 upgrade");
}
catch(Exception $e) { }

$installer->endSetup();

 