<?php
/** @return Mage_Core_Model_Resource_Setup */
$installer = $this;

/** DB Updates **/
$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('authorizenetcim_profile')};
CREATE TABLE {$this->getTable('authorizenetcim_profile')} (
    `profile_id` int(10) unsigned NOT NULL auto_increment,
    `customer_id` int(10) unsigned NOT NULL,
    `gateway_id` bigint(8) DEFAULT NULL,
    PRIMARY KEY  (`profile_id`),
    KEY `FK_AUTHORIZENETCIM_CUSTOMER_ID` (`customer_id`),    
    CONSTRAINT `FK_AUTHORIZENETCIM_CUSTOMER_ID` FOREIGN KEY (`customer_id`) REFERENCES `{$this->getTable('customer_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

/** End DB Updates **/

$installer->endSetup();