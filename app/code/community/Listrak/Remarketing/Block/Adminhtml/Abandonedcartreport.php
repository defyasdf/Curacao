<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Block_Adminhtml_Abandonedcartreport extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_abandonedcartreport';
        $this->_blockGroup = 'remarketing';
        $this->_headerText = Mage::helper('remarketing')->__('Abandoned Carts');
        parent::__construct();
		$this->_removeButton('add');
    }
}