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
class Magify_BannerManager_Block_Adminhtml_Bannermanager_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('magify_bannermanager_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('magify_bannermanager')->__('Banner Options'));
	}
	
    protected function _beforeToHtml()
    {
    	Mage::dispatchEvent('magify_bannermanager_adminhtml_banner_tabs_before', array('object' => $this));
    	    	
		$this->addTab('main_form', array(
        	'label'     => Mage::helper('magify_bannermanager')->__('General'),
        	'content'   => $this->_translateHtml($this->getLayout()
            	->createBlock('magify_bannermanager/adminhtml_bannermanager_edit_tab_form')->toHtml()),
		));
		
		/* Dynamic Link coming soon...
		$this->addTab('banner_link', array(
			'label'     => Mage::helper('magify_bannermanager')->__('Link Settings'),
        	'content'   => $this->_translateHtml($this->getLayout()
            	->createBlock('magify_bannermanager/adminhtml_bannermanager_edit_tab_link')->toHtml()),
		));
		*/
		
    	$this->addTab('banner_resource', array(
			'label'     => Mage::helper('magify_bannermanager')->__('Content'),
        	'content'   => $this->_translateHtml($this->getLayout()
            	->createBlock('magify_bannermanager/adminhtml_bannermanager_edit_tab_resource')->toHtml()),
		));
		
    	if(in_array(Mage::getStoreConfig('magify_bannermanager/settings/mode'), array(1, 2)))
    	{		
			$this->addTab('banner_schedule', array(
				'label'     => Mage::helper('magify_bannermanager')->__('Schedule'),
	        	'content'   => $this->_translateHtml($this->getLayout()
	            	->createBlock('magify_bannermanager/adminhtml_bannermanager_edit_tab_schedule')->toHtml()),
			));
		
	    	$this->addTab('banner_locations', array(
				'label'     => Mage::helper('magify_bannermanager')->__('Location'),
	        	'content'   => $this->_translateHtml($this->getLayout()
	            	->createBlock('magify_bannermanager/adminhtml_bannermanager_edit_tab_locations')->toHtml()),
			));				
    	}

    	/*		
    	$this->addTab('associated_products', array(
			'label'     => Mage::helper('magify_bannermanager')->__('Associated Products'),
			'url'       => $this->getUrl('* /* /productsgrid', array('_current' => true)),
			'class'     => 'ajax',
		));
		*/
    	/*
    	$this->addTab('history_log', array(
			'label'     => Mage::helper('magify_bannermanager')->__('History Log'),
			'url'       => $this->getUrl('* /* /history', array('_current' => true)),
			'class'     => 'ajax',
		));
		*/
    	
    	Mage::dispatchEvent('magify_bannermanager_adminhtml_banner_tabs_after', array('object' => $this));
		
		$this->_updateActiveTab();
		
		return parent::_beforeToHtml();
    }
    
    protected function _updateActiveTab()
    {
        $tabId = $this->getRequest()->getParam('tab');
        if($tabId)
        {
            $tabId = preg_replace("#{$this->getId()}_#", '', $tabId);
            if($tabId)
            {
                $this->setActiveTab($tabId);
            }
        }
    }
        
    /**
     * Translate html content
     *
     * @param string $html
     * @return string
     */
    protected function _translateHtml($html)
    {
        Mage::getSingleton('core/translate_inline')->processResponseBody($html);
        return $html;
    }
        
}