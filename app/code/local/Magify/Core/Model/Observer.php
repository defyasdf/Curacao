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
 * @package     Magify_Core
 * @copyright   Copyright (c) 2012 Magify Commerce (http://www.magify.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Magify_Core_Model_Observer
{
	public function addJquery($observer)
	{
		$block = $observer->getEvent()->getBlock();
		if($block instanceof Mage_Page_Block_Html_Head)
		{
			if(Mage::getStoreConfig('magify_core/scripts/jquery_enabled') && !is_null(Mage::getStoreConfig('magify_core/scripts/jquery_file')))
			{
				$block->addItem('js', Mage::getStoreConfig('magify_core/scripts/jquery_file'), '');
			}
			if(Mage::getStoreConfig('magify_core/scripts/jquerytools_enabled') && !is_null(Mage::getStoreConfig('magify_core/scripts/jquerytools_file')))
			{
				$block->addItem('js', Mage::getStoreConfig('magify_core/scripts/jquerytools_file'), '');
			}
		}
		return $observer;
	}
	
	/*
	public function clearCache($observer)
	{
		Mage::log('test');
		$parsedFiles = unserialize(Mage::app()->loadCache('magify_core_less_parsed_files'));
		foreach($parsedFiles as $file)
		{
			echo $file;
		}
	}
	
	public function saveParsedFiles($observer)
	{
		$block = $observer->getEvent()->getBlock();
		if($block instanceof Magify_Core_Block_Page_Html_Head)
		{
			Mage::log('test');
			Mage::log($block->getParsedFiles());
						
			Mage::app()->saveCache(serialize($block->getParsedFiles()), 'magify_core_less_parsed_files');
		}
	}
	*/
}