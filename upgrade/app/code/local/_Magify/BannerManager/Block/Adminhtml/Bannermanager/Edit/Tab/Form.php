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
class Magify_BannerManager_Block_Adminhtml_Bannermanager_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareLayout()
    {
    	parent::_prepareLayout();
    }

    protected function _prepareForm()
	{
		$model = Mage::registry('magify_bannermanager_current_banner');
		
		/*
         * Checking if user have permissions to save information
         */
        if ($this->_isAllowedAction('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }
				
		
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('magify_bannermanager_');

        $this->setForm($form);
        
        $fieldset = $form->addFieldset('base_fieldset',	array('legend' => Mage::helper('magify_bannermanager')->__('General Settings')));
        
        if($model->getId()) $fieldset->addField('id', 'hidden', array('name' => 'id'));
        
		$fieldset->addField('title', 'text', array(
			'name'      	=> 'title',
			'label'     	=> Mage::helper('magify_bannermanager')->__('Title'),
			'title'     	=> Mage::helper('magify_bannermanager')->__('Title'),
			'required'  	=> true,
		));
        
		$fieldset->addField('link', 'text', array(
			'name'      	=> 'link',
			'label'     	=> Mage::helper('magify_bannermanager')->__('Link'),
			'title'     	=> Mage::helper('magify_bannermanager')->__('Link'),
			'disabled'  	=> $isElementDisabled,
		));
		
    	if(in_array(Mage::getStoreConfig('magify_bannermanager/settings/mode'), array(1, 2)))
    	{
	        $fieldset->addField('position', 'select', array(
	            'label'     => Mage::helper('magify_bannermanager')->__('Position'),
	            'title'     => Mage::helper('magify_bannermanager')->__('Position'),
	            'name'      => 'position',
	            'required'  => true,
	            'options'   => $model->getAvailablePositions(),
	            'disabled'  => $isElementDisabled,
	        ));
    	}

		/**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('banner_store', 'multiselect', array(
                'name'      => 'banner_store[]',
                'label'     => Mage::helper('magify_bannermanager')->__('Store View'),
                'title'     => Mage::helper('magify_bannermanager')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled'  => $isElementDisabled
            ));
        }
        else {
            $fieldset->addField('banner_store', 'hidden', array(
                'name'      => 'banner_store[]',
                'value'     => Mage::app()->getStore(true)->getId(),
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('status', 'select', array(
            'label'     => Mage::helper('magify_bannermanager')->__('Status'),
            'title'     => Mage::helper('magify_bannermanager')->__('Banner Status'),
            'name'      => 'status',
            'required'  => true,
            'options'   => $model->getAvailableStatuses(),
            'disabled'  => $isElementDisabled,
        ));
        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '0' : '1');
        }
        
    	if(in_array(Mage::getStoreConfig('magify_bannermanager/settings/mode'), array(1, 2)))
    	{
	    	$customerGroups = Mage::getResourceModel('customer/group_collection')
	            ->load()->toOptionArray();
	
	        $found = false;
	        foreach($customerGroups as $group)
			{
	            if($group['value']==0)
				{
	                $found = true;
	            }
				$customGroupIds[] = $group['value'];
	        }
	        if(!$found)
			{
	            array_unshift($customerGroups, array('value' => 0, 'label' => Mage::helper('customer')->__('NOT LOGGED IN')));
	        }
					
	        $fieldset->addField('customer_group', 'multiselect', array(
	            'name'      	=> 'customer_group',
	            'label'     	=> Mage::helper('customer')->__('Customer Groups'),
	            'title'     	=> Mage::helper('customer')->__('Customer Groups'),
	            'required'  	=> true,
	            'style'     	=> 'height:7.5em;',
	            'values'   		=> $customerGroups,
	            'disabled'  => $isElementDisabled,
	        ));
    	}
    	   
		$fieldset->addField('banner_click_count', 'text', array(
			'name'      	=> 'banner_click_count',
			'label'     	=> Mage::helper('magify_bannermanager')->__('Total Clicks'),
			'title'     	=> Mage::helper('magify_bannermanager')->__('Total Clicks'),
			'disabled'  	=> true,
			'note'			=> 'Read only',
		));
        
		$fieldset->addField('banner_impression_count', 'text', array(
			'name'      	=> 'banner_impression_count',
			'label'     	=> Mage::helper('magify_bannermanager')->__('Total Impressions'),
			'title'     	=> Mage::helper('magify_bannermanager')->__('Total Impressions'),
			'disabled'  	=> true,
			'note'			=> 'Read only',
		));
		
		Mage::dispatchEvent('magify_adminhtml_bannermanager_edit_tab_form_prepare_form', array('form' => $form));
              
        $form->setValues($model->getData());
        
		$form->setUseContainer(false);
		
		return parent::_prepareForm();
    }
	    
	/**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('magify_bannermanager')->__('General Settings');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('magify_bannermanager')->__('General Settings');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
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