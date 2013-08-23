<?php
/*******************************************
Magify
This source file is subject to the Magify Software License, which is available at http://magify.com/license/.
Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
If you wish to customize this module for your needs
Please refer to http://www.magentocommerce.com for more information.
@category Magify
@copyright Copyright (C) 2013 Magify (http://magify.com.ua)
*******************************************/

$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('misspell/misspell')} CHANGE `freq` `freq` DECIMAL(12,4) NOT NULL DEFAULT '0';
");

$installer->endSetup();