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
class Magify_BannerManager_Block_Adminhtml_Bannermanager_Groups_Edit_Tab_Locations extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('locations_magify_bannermanager_locations_tabs');
        $this->setDestElementId('locations_magify_bannermanager_locations_tab_content');
        $this->setTitle(Mage::helper('magify_bannermanager')->__('Banner Locations Data'));
        $this->setTemplate('magify/widget/tabshoriz.phtml');
    }
    	
    protected function _prepareLayout()
    {
    	$this->addTab('banner_locations_categories', array(
			'label'     => Mage::helper('magify_bannermanager')->__('Categories'),
        	'content'   => $this->_translateHtml($this->getLayout()
            	->createBlock('magify_bannermanager/adminhtml_bannermanager_groups_edit_tab_locations_categories')->toHtml()),
		));
		
    	$this->addTab('banner_locations_products', array(
			'label'     => Mage::helper('magify_bannermanager')->__('Products'),
			'url'       => $this->getUrl('*/*/locationsproductsgrid', array('_current' => true)),
			'class'     => 'ajax',
		));

		$this->addTab('banner_locations_pages', array(
			'label'     => Mage::helper('magify_bannermanager')->__('Pages'),
			'url'       => $this->getUrl('*/*/locationspagesgrid', array('_current' => true)),
			'class'     => 'ajax',
		));

		$this->addTab('banner_locations_custom', array(
			'label'     => Mage::helper('magify_bannermanager')->__('Custom'),
			'url'       => $this->getUrl('*/*/locationscustomgrid', array('_current' => true)),
			'class'     => 'ajax',
		));
				
        // dispatch event add custom tabs
        Mage::dispatchEvent('magify_bannermanger_adminhtml_bannermanager_edit_tab_locations_tabs', array(
            'tabs'  => $this
        ));

        parent::_prepareLayout();
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
        
 
    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/magify_bannermanager/' . $action);
    }	
}