<?php

class Magify_AjaxLogin_Model_System_Config_Backend_Mode extends Mage_Adminhtml_Model_System_Config_Backend_Cache
{
	/**
	 * Cache tags to clean
	 *
	 * @var array
	 */
	protected $_cacheTags = array(Mage_Core_Model_Store::CACHE_TAG, Mage_Cms_Model_Block::CACHE_TAG);

}