<?php

$installer = $this;

$installer->startSetup();

$installer->run("
		
	ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `curacaocustomernumber` VARCHAR(255) NOT NULL;
        ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `curacaocustomerdiscount` DECIMAL( 10, 2 ) NOT NULL;

    ");

$installer->endSetup(); 