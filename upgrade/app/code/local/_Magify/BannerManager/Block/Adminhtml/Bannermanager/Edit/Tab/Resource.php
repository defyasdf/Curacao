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
class Magify_BannerManager_Block_Adminhtml_Bannermanager_Edit_Tab_Resource extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
	protected $_prefix;
	
    protected function _construct()
    {
        parent::_construct();
        $this->setActive(true);
        $this->_prefix = 'magify_bannermanager_';
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
        $form->setHtmlIdPrefix($this->_prefix);

        $this->setForm($form);
        
        $fieldset = $form->addFieldset('resource_fieldset',	array('legend' => Mage::helper('magify_bannermanager')->__('Content')));		

        $script = '
        	<script type="text/javascript">
				//<![CDATA[
				function switchResource() {
				    var value = $("' . $this->_prefix . 'resource_type").options[$("' . $this->_prefix . 'resource_type").selectedIndex].value;
				    if(value=="' . Magify_BannerManager_Model_Banner::STATIC_RESOURCE . '") {
				    	$("' . $this->_prefix . Magify_BannerManager_Model_Banner::FILE_RESOURCE . '").up("tr").hide();
				    	$("' . $this->_prefix . Magify_BannerManager_Model_Banner::STATIC_RESOURCE . '").up("tr").show();
				    } else {
				    	$("' . $this->_prefix . Magify_BannerManager_Model_Banner::STATIC_RESOURCE . '").up("tr").hide();
				    	$("' . $this->_prefix . Magify_BannerManager_Model_Banner::FILE_RESOURCE . '").up("tr").show();
					}
				}
				document.observe("dom:loaded", function() {
					switchResource();
				});			
				//]]>
        	</script>
        ';
        
        $fieldset->addField('resource_type', 'select', array(
            'label'     			=> Mage::helper('magify_bannermanager')->__('Content Type'),
            'title'     			=> Mage::helper('magify_bannermanager')->__('Content Type'),
            'name'      			=> 'resource_type',
            'required'  			=> true,
            'options'   			=> $model->getResourceTypes(),
	      	'onchange'				=> 'switchResource()',
            'after_element_html' 	=> $script,
        	'disabled'  			=> $isElementDisabled,
        ));
        
		$fieldset->addField('content', 'textarea', array(
			'name'      	=> 'content',
			'label'     	=> Mage::helper('magify_bannermanager')->__('Custom HTML'),
			'title'     	=> Mage::helper('magify_bannermanager')->__('Custom HTML'),
			'required'  	=> false,
			'class'			=> 'resource_option',
			'notes'			=> 'Output will not be filtered!',
            'config' 		=> Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'wysiwyg' 		=> false,		
		));
        
		$fieldset->addField('file', 'image', array(
			'name'      	=> 'file',
			'class'			=> 'resource_option',
			'label'     	=> Mage::helper('magify_bannermanager')->__('File'),
			'title'     	=> Mage::helper('magify_bannermanager')->__('File'),
			'required'  	=> false,
		));

		if(Mage::getStoreConfig('magify_bannermanager/settings/fluid_background'))
		{
			$fieldset->addField('background_color', 'colourswatch', array(
				'name'      			=> 'background_color',
				'label'     			=> Mage::helper('magify_bannermanager')->__('Background Color'),
				'title'     			=> Mage::helper('magify_bannermanager')->__('Background Color'),
				'required'  			=> true,
				'notes'					=> 'Leave blank for no background color.',
			));
		}
		Mage::dispatchEvent('magify_adminhtml_bannermanager_edit_tab_resource_prepare_form', array('form' => $form));
              
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
        return Mage::helper('magify_bannermanager')->__('Content');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('magify_bannermanager')->__('Content');
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