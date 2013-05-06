<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Block_Adminhtml_EmailCapture_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
               
        $this->_objectId = 'id';
        $this->_blockGroup = 'remarketing';
        $this->_controller = 'adminhtml_emailcapture';
 
        $this->_updateButton('save', 'label', Mage::helper('remarketing')->__('Save Field'));
        $this->_updateButton('delete', 'label', Mage::helper('remarketing')->__('Delete Field'));
    }
 
    public function getHeaderText()
    {
        if( Mage::registry('emailcapture_data') && Mage::registry('emailcapture_data')->getId() ) {
            return Mage::helper('remarketing')->__("Edit Field");
        } else {
            return Mage::helper('remarketing')->__('Add Field');
        }
    }
}