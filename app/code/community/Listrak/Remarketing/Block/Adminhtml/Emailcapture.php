<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Block_Adminhtml_EmailCapture extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_emailcapture';
        $this->_blockGroup = 'remarketing';
        $this->_headerText = Mage::helper('remarketing')->__('Field Manager');
        $this->_addButtonLabel = Mage::helper('remarketing')->__('Add Field');
        parent::__construct();
    }
}