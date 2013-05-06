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
class Magify_BannerManager_Block_Adminhtml_Bannermanager_Groups_Edit_Tab_Template extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareLayout()
    {
    	parent::_prepareLayout();
    }

    protected function _prepareForm()
	{
		$model = Mage::registry('magify_bannermanager_current_banner_group');
		
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
        
        $fieldset = $form->addFieldset('template_fieldset',	array('legend' => Mage::helper('magify_bannermanager')->__('Template')));
                
        $fieldset->addField('params[frontend]', 'select', array(
            'label'     	=> Mage::helper('magify_bannermanager')->__('Template'),
            'title'     	=> Mage::helper('magify_bannermanager')->__('Template'),
            'required'  	=> true,
            'options'   	=> $model->getAvailableFrontends(),
            'disabled'  	=> $isElementDisabled,
        	'name'			=> 'params[frontend]',
        	'note'			=> 'Save and Continue to see template params.',
        ));
        
		$fieldset->addField('params[template_override]', 'hidden', array(
			'label'     	=> Mage::helper('magify_bannermanager')->__('Template Path'),
			'title'     	=> Mage::helper('magify_bannermanager')->__('Template Path'),
			'required'  	=> false,
        	'name'			=> 'params[template_override]',
			'disabled'  	=> $isElementDisabled,
		));
                
        $fieldset->addField('params_resize_height', 'text', array(
            'label'     	=> Mage::helper('magify_bannermanager')->__('Resize Height'),
            'title'     	=> Mage::helper('magify_bannermanager')->__('Resize Height'),
            'required'  	=> true,
            'disabled'  	=> $isElementDisabled,
        	'name'			=> 'params[resize][height]',
        	'note'			=> 'Leave blank for no resize.',
        ));
        $model->setData('params_resize_height', $model->getData('params/resize/height'));
        		
        $fieldset->addField('params_resize_width', 'text', array(
            'label'     	=> Mage::helper('magify_bannermanager')->__('Resize Width'),
            'title'     	=> Mage::helper('magify_bannermanager')->__('Resize Width'),
            'required'  	=> true,
            'disabled'  	=> $isElementDisabled,
        	'name'			=> 'params[resize][width]',
        	'note'			=> 'Leave blank for no resize.',
        ));
        $model->setData('params_resize_width', $model->getData('params/resize/width'));
		
        
		if($frontend = $model->getData('params/frontend'))
		{
						
			$availableOptions = $model->getFrontends($frontend);
			
			if(isset($availableOptions['params']))
			{
       			$fieldset = $form->addFieldset('params_fieldset',	array('legend' => Mage::helper('magify_bannermanager')->__('Parameters')));
				
				$defaults['params'] = $model->getData('params/params');
				//print_r($defaults);die(0);
				foreach($availableOptions['params'] as $key => $option)
				{
					$data = array();
					$data['label'] 		= (isset($option['title'])) ? $option['title'] : $key;
					$data['title'] 		= (isset($option['title'])) ? $option['title'] : $key;
					$data['required'] 	= (isset($option['required'])) ? true : false;
					$data['name'] 		= 'params[params]['. $key .']';
					$data['disabled'] 	= $isElementDisabled;
					
					if(in_array($option['type'], array('select', 'multiselect')) && isset($option['options']))
					{
						$data['values'] = $this->optionGetter($option['options']);
					}
					
					if(isset($option['default']))
					{
						if($model->getData('params/params/' . $key))
						{
							$model->setData('params_' . $key, $model->getData('params/params/' . $key));
						}
						else
						{
							$model->setData('params_' . $key, $option['default']);
						}
					}
					
					$data['note'] = (isset($option['note'])) ? $option['note'] : '';
					
					$fieldset->addField('params_'. $key .'', (isset($option['type'])) ? $option['type'] : 'text', $data);
				}
				
				$fieldset->addField($frontend . '_documentation', 'link', array(
					'title'		=> 'test',
					'label'		=> 'Documentation',
					'href'		=> $availableOptions['url'],
					'name'		=> $availableOptions['title'],
					'value'		=> 'asdasdas',
				));
				
				$model->setData($frontend . '_documentation', $availableOptions['title']);
												
			}
			
		}
		
		Mage::dispatchEvent('magify_adminhtml_bannermanager_group_edit_tab_template_prepare_form', array('form' => $form));
              
        $form->setValues($model->getData());
        
		$form->setUseContainer(false);
		
		return parent::_prepareForm();
    }
    
    public function optionGetter($options)
    {
    	$formatted = array();
    	
    	foreach($options as $value => $title)
    	{
    		if($value == 'yes') $value = 1;
    		if($value == 'no')	$value = 0;
    		
    		$formatted[] = array('label' => ($title) ? $title : $value, 'value' => $value);		    		
    	}
    	return $formatted;
    }
	    
	/**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('magify_bannermanager')->__('Template');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('magify_bannermanager')->__('Template');
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
        return Mage::getSingleton('admin/session')->isAllowed('cms/magify_bannermanager/group/' . $action);
    }	
}