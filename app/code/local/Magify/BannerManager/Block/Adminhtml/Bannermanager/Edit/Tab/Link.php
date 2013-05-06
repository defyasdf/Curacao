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
class Magify_BannerManager_Block_Adminhtml_Bannermanager_Edit_Tab_Link extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _construct()
    {
        parent::_construct();
        $this->setActive(true);
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
        
        $fieldset = $form->addFieldset('link_fieldset',	array('legend' => Mage::helper('magify_bannermanager')->__('Link Settings')));		

        $script = '
        	<script type="text/javascript">
				//<![CDATA[
				function loadLinkModel(urlTemplate) {
				    var linktype = $("magify_bannermanager_link_type")[$("magify_bannermanager_link_type").selectedIndex].value;
				    url = urlTemplate.replace("{{linktype}}", linktype);
				    
					new Ajax.Request(
				        url,
				        {
				            method:"post",
				            postBody:"",
				            onException: function(resp, e)
				            {
				                alert("Exception : " + e);
				            },
				            onComplete: function(resp)
				            {
				    			try
				    			{
				    				if(typeof(resp.responseText) == "string") eval("resp = " + resp.responseText);			
				    			}
				    			catch(e)
				    			{	
				    				$("linktypehtml").insert(resp.responseText);	
									return;
				    			}
				            }
				        });
				    
				}
				 
				//]]>
        	</script>
        	<div id="linktypehtml"></div>
        ';
        
        $fieldset->addField('link_type', 'select', array(
            'label'     			=> Mage::helper('magify_bannermanager')->__('Link Type'),
            'title'     			=> Mage::helper('magify_bannermanager')->__('Link Type'),
            'name'      			=> 'link_type',
            'required'  			=> true,
            'options'   			=> $model->getLinkTypesOptionArray(),
            'disabled'  			=> $isElementDisabled,
	      	'onchange'				=> 'loadLinkModel(\'' . $this->getUrl('*/*/link{{linktype}}'). '\')',
            'after_element_html' 	=> $script,
        ));
        
		Mage::dispatchEvent('magify_adminhtml_bannermanager_edit_tab_link_prepare_form', array('form' => $form));
              
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
        return Mage::helper('magify_bannermanager')->__('Link Settings');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('magify_bannermanager')->__('Link Settings');
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