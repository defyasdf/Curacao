<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

$installer = $this;
$installer->startSetup();

$installer->run("
INSERT INTO {$this->getTable('listrak/emailcapture')} (`emailcapture_id` ,`page` ,`field_id`) VALUES (NULL , '*', 'ltkmodal-email');
");
 
try {
	Mage::getModel("listrak/log")->addMessage("1.0.8-1.0.9 upgrade");
}
catch(Exception $e) { }

$installer->endSetup();