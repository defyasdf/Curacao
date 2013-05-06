<?php
/**
* @author Magify Team
* @copyright Magify
* @package Magify_Conf
*/
class Magify_Conf_Block_Adminhtml_Catalog_Product_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Attribute_Edit_Tabs
{
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        if (Mage::registry('entity_attribute')->getIsConfigurable() && Mage::registry('entity_attribute')->getIsGlobal())
        {
            $imgBlock = $this->getLayout()->createBlock('mconf/adminhtml_catalog_product_attribute_edit_tab_images');
            if ($imgBlock)
            {
                $this->addTab('images', array(
                    'label'     => Mage::helper('mconf')->__('Attribute Images'),
                    'title'     => Mage::helper('mconf')->__('Attribute Images'),
                    'content'   => $imgBlock->toHtml(),
                ));
                return Mage_Adminhtml_Block_Widget_Tabs::_beforeToHtml();
            }
        }
        
        return $this;
    }
}
