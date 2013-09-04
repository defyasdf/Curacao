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
$installer->run("DROP TABLE IF EXISTS `{$installer->getTable('misspell/misspell')}`;");
$installer->run("
CREATE TABLE `{$installer->getTable('misspell/misspell')}` (
    `misspell_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Misspell Id',
    `keyword` varchar(255) NOT NULL COMMENT 'Keyword',
    `trigram` varchar(255) NOT NULL COMMENT 'Trigram',
    `freq` decimal(12,4) NOT NULL DEFAULT '0.0000' COMMENT 'Frequency',
    PRIMARY KEY (`misspell_id`),
    FULLTEXT KEY `FTI_M_MISSPELL_TRIGRAM` (`trigram`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Misspell';
");

$installer->endSetup();