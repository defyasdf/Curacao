<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Block_Adminhtml_EmailCapture_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
 
    public function __construct()
    {
        parent::__construct();
        $this->setId('emailcapture_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('remarketing')->__('Field Information'));
    }
 
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('remarketing')->__('Field Information'),
            'title'     => Mage::helper('remarketing')->__('Field Information'),
            'content'   => $this->getLayout()->createBlock('remarketing/adminhtml_emailcapture_edit_tab_form')->toHtml(),
        ));
       
        return parent::_beforeToHtml();
    }
}