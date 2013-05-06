<?php 
/**
 * Magify Commerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magify.com so we can send you a copy immediately.
 *
 * @category    Magify
 * @package     Magify_BannerManager
 * @copyright   Copyright (c) 2012 Magify Commerce (http://www.magify.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

// Main Banner Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner')} (
		`id` smallint(10) NOT NULL auto_increment,
		`title` varchar(255) NOT NULL default '',
		`sort` smallint(8) NOT NULL,
		`type_id` smallint(5) NOT NULL,
		`created_date` datetime NULL,
		`update_date` datetime NULL,
		`content` text,
		`file` varchar(255) NOT NULL default '',
		`background_color` varchar(32) NOT NULL default '',
		`status` tinyint(1) NOT NULL default 0,
		`link` varchar(255) NOT NULL default '',
		`position` varchar(255) NOT NULL default '',
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

// Banner Store Association Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_store')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_store')} (
		`id` smallint(10) NOT NULL auto_increment,
		`banner_id` smallint(10) NOT NULL,
		`store_id` smallint(5) unsigned NOT NULL,
		PRIMARY KEY (`id`,`banner_id`,`store_id`),
		CONSTRAINT `FK_BANNER_STORE_TO_BANNER` FOREIGN KEY (`banner_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  		CONSTRAINT `FK_BANNER_STORE_TO_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Banner Store Association.';
");

// Banner Schedule Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_schedule')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_schedule')} (
		`id` smallint(10) NOT NULL auto_increment,
		`banner_id` smallint(10) NOT NULL,
		`type_id` smallint(5) NOT NULL,
		`from_date` date NULL,
		`to_date` date NULL,
		PRIMARY KEY (`id`,`banner_id`,`type_id`),
		CONSTRAINT `FK_BANNER_SCHEDULE_TO_BANNER` FOREIGN KEY (`banner_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Banner Schedule.';
");

// Banner Link Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_link')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_link')} (
		`id` smallint(10) NOT NULL auto_increment,
		`banner_id` smallint(10) NOT NULL,
		`entity_type` smallint(5) NOT NULL,
		`entity_id` smallint(10) NOT NULL,
		`params` varchar(255) NOT NULL default '',
		PRIMARY KEY (`id`,`banner_id`,`entity_type`,`entity_id`),
		CONSTRAINT `FK_BANNER_LINK_TO_BANNER` FOREIGN KEY (`banner_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_customergroup')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_customergroup')} (
		`banner_id` smallint(10) NOT NULL,
		`group_id` smallint(5) NOT NULL,
		PRIMARY KEY (`banner_id`,`group_id`),
		CONSTRAINT `FK_BANNER_CUSTOMERGROUP_TO_BANNER` FOREIGN KEY (`banner_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
		CONSTRAINT `FK_BANNER_CUSTOMERGROUP_TO_CUSTOMERGROUP` FOREIGN KEY (`group_id`) REFERENCES `{$this->getTable('customer_group')}` (`customer_group_id`) ON UPDATE CASCADE ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

/*
// Main Banner Resource File Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_resource_file')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_resource_file')} (
		`id` smallint(10) NOT NULL auto_increment,
		`banner_id` smallint(10) NOT NULL,
		`value` varchar(255) NOT NULL default '',
		PRIMARY KEY (`id`,`banner_id`),
		CONSTRAINT `FK_BANNER_RESOURCE_FILE_TO_BANNER` FOREIGN KEY (`banner_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

// Main Banner Resource Static Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_resource_static')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_resource_static')} (
		`id` smallint(10) NOT NULL auto_increment,
		`banner_id` smallint(10) NOT NULL,
		`value` text,
		PRIMARY KEY (`id`,`banner_id`),
		CONSTRAINT `FK_BANNER_RESOURCE_STATIC_TO_BANNER` FOREIGN KEY (`banner_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
*/

// Main Banner Impressions Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_impression')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_impression')} (
		`id` smallint(10) NOT NULL auto_increment,
		`banner_id` smallint(10) NOT NULL,
		`date` datetime NULL,
		PRIMARY KEY (`id`,`banner_id`),
		CONSTRAINT `FK_BANNER_IMPRESSION_TO_BANNER` FOREIGN KEY (`banner_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

// Main Banner Click Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_click')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_click')} (
		`id` smallint(10) NOT NULL auto_increment,
		`banner_id` smallint(10) NOT NULL,
		`date` datetime NULL,
		PRIMARY KEY (`id`,`banner_id`),
		CONSTRAINT `FK_BANNER_CLICK_TO_BANNER` FOREIGN KEY (`banner_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

// Main Banner Associated Entities Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_associated_entities')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_associated_entities')} (
		`id` smallint(10) NOT NULL auto_increment,
		`banner_id` smallint(10) NOT NULL,
		`entity_type_id` smallint(10) NOT NULL,
		`entity_id` smallint(10) NOT NULL,
		`position` smallint(5) NOT NULL,
		PRIMARY KEY (`id`,`banner_id`,`entity_type_id`,`entity_id`),
		CONSTRAINT `FK_BANNER_GROUP_BANNER_ASSOCIATION_TO_BANNER` FOREIGN KEY (`banner_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");


// Main Banner Group Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_group')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_group')} (
		`id` smallint(10) NOT NULL auto_increment,
		`title` varchar(255) NOT NULL default '',
		`params` text,
		`sort` smallint(8) NOT NULL default 0,
	  	`filter_children` tinyint(1) NOT NULL default 0,
		`created_date` datetime NULL,
		`update_date` datetime NULL,
		`status` tinyint(1) NOT NULL default 0,
		`page_position` varchar(255) NOT NULL default '',
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

// Banner Group Store Association Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_group_store')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_group_store')} (
		`id` smallint(10) NOT NULL auto_increment,
		`group_id` smallint(10) NOT NULL,
		`store_id` smallint(5) unsigned NOT NULL,
		PRIMARY KEY (`id`,`group_id`,`store_id`),
		CONSTRAINT `FK_BANNER_GROUP_STORE_TO_BANNER_GROUP` FOREIGN KEY (`group_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner_group')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  		CONSTRAINT `FK_BANNER_GROUP_STORE_TO_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Banner Group Store Association.';
");

// Banner Group Children Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_group_children')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_group_children')} (
		`id` smallint(10) NOT NULL auto_increment,
		`group_id` smallint(10) NOT NULL,
		`banner_id` smallint(10) NOT NULL,
		`position` smallint(5) NOT NULL,
		PRIMARY KEY (`id`,`banner_id`,`group_id`),
		CONSTRAINT `FK_BANNER_GROUP_CHILD_TO_BANNER_GROUP_GROUP_ID` FOREIGN KEY (`group_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner_group')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
		CONSTRAINT `FK_BANNER_GROUP_CHILD_TO_BANNER_GROUP_BANNER_ID` FOREIGN KEY (`banner_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

// Banner Group Impressions Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_group_impression')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_group_impression')} (
		`id` smallint(10) NOT NULL auto_increment,
		`group_id` smallint(10) NOT NULL,
		`date` datetime NULL,
		PRIMARY KEY (`id`,`group_id`),
		CONSTRAINT `FK_BANNER_GROUP_IMPRESSION_TO_BANNER_GROUP` FOREIGN KEY (`group_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner_group')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

// Banner Group Schedule Table //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_group_schedule')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_group_schedule')} (
		`id` smallint(10) NOT NULL auto_increment,
		`group_id` smallint(10) NOT NULL,
		`type_id` smallint(5) NOT NULL,
		`from_date` date NULL,
		`to_date` date NULL,
		PRIMARY KEY (`id`,`group_id`,`type_id`),
		CONSTRAINT `FK_BANNER_GROUP_SCHEDULE_TO_BANNER_GROUP` FOREIGN KEY (`group_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner_group')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Banner Group Schedule.';
");

// Main Banner History Log //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_history')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_history')} (
		`id` smallint(10) NOT NULL auto_increment,
		`banner_id` smallint(10) NOT NULL,
		`change_log` text,
		`restore_data` text,
		`user_id` int(10) NOT NULL,
		`date` datetime NULL,
		PRIMARY KEY (`id`,`banner_id`),
		CONSTRAINT `FK_BANNER_HISTORY_TO_BANNER` FOREIGN KEY (`banner_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

// Main Banner Locations //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_location')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_location')} (
		`id` smallint(10) NOT NULL auto_increment,
		`banner_id` smallint(10) NOT NULL,
		`entity_type_id` smallint(5) NOT NULL,
		`entity_id` int(10) NOT NULL,
		PRIMARY KEY (`id`,`banner_id`),
		CONSTRAINT `FK_BANNER_LOCATIONS_TO_BANNER` FOREIGN KEY (`banner_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");


// Main Banner Group Locations //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_group_location')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_group_location')} (
		`id` smallint(10) NOT NULL auto_increment,
		`group_id` smallint(10) NOT NULL,
		`entity_type_id` smallint(5) NOT NULL,
		`entity_id` int(10) NOT NULL,
		PRIMARY KEY (`id`,`group_id`),
		CONSTRAINT `FK_BANNER_GROUP_LOCATIONS_TO_BANNER_GROUP` FOREIGN KEY (`group_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner_group')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

// Main Banner Group Customer Groups //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/banner_group_customergroup')};
	CREATE TABLE {$this->getTable('magify_bannermanager/banner_group_customergroup')} (
		`group_id` smallint(10) NOT NULL,
		`customer_group_id` smallint(5) NOT NULL,
		PRIMARY KEY (`group_id`,`customer_group_id`),
		CONSTRAINT `FK_BANNER_GROUP_CUSTOMERGROUP_TO_BANNER_GROUP` FOREIGN KEY (`group_id`) REFERENCES `{$this->getTable('magify_bannermanager/banner_group')}` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
		CONSTRAINT `FK_BANNER_GROUP_CUSTOMERGROUP_TO_CUSTOMERGROUP` FOREIGN KEY (`customer_group_id`) REFERENCES `{$this->getTable('customer_group')}` (`customer_group_id`) ON UPDATE CASCADE ON DELETE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");


// Main Custom Locations //
$installer->run("
	DROP TABLE IF EXISTS {$this->getTable('magify_bannermanager/custom')};
	CREATE TABLE {$this->getTable('magify_bannermanager/custom')} (
		`id` smallint(10) NOT NULL auto_increment,
		`module` varchar(255) NOT NULL default '',
		`controller` varchar(255) NOT NULL default '',
		`view` varchar(255) NOT NULL default '',
		`key` varchar(255) NOT NULL default '',
		`title` varchar(255) NOT NULL default '',
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
$installer->run("
	INSERT INTO {$this->getTable('magify_bannermanager/custom')} (`id`, `module`, `controller`, `view`, `key`, `title`) VALUES
	(1, 'checkout', 'cart', 'index', 'checkout_cart_index', 'Shopping Cart'),
	(2, 'checkout', 'onepage', 'index', 'checkout_onepage_index', 'OnePage Checkout'),
	(3, 'checkout', 'onepage', 'success', 'checkout_onepage_success', 'Checkout Success'),
	(4, 'checkout', 'onepage', 'failure', 'checkout_onepage_failure', 'Checkout Failure'),
	(5, 'checkout', 'multishipping', '', 'checkout_multishipping', 'Multishipping Checkout'),
	(6, 'checkout', 'multishipping', 'success', 'checkout_multishipping_success', 'Multishipping Success'),
	(7, 'customer', 'account', 'login', 'customer_account_login', 'Login'),
	(8, 'customer', 'account', 'forgotpassword', 'customer_account_forgotpassword', 'Forgot Password'),
	(9, 'customer', 'account', 'create', 'customer_account_create', 'Register'),
	(10, 'customer', 'account', 'confirmation', 'customer_account_confirmation', 'Register Confirmation'),
	(11, 'customer', 'account', 'index', 'customer_account_index', 'My Account'),
	(12, 'contacts', 'index', 'index', 'contacts_index_index', 'Contact Us');
	
");


$installer->endSetup();