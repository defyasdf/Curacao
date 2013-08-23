<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Block_Adminhtml_Sales_Order_Invoice_Create_Items extends Mage_Adminhtml_Block_Sales_Order_Invoice_Create_Items
{
    /**
     * Prepare child blocks
     *
     * @return Mage_Adminhtml_Block_Sales_Order_Invoice_Create_Items
     */
    protected function _beforeToHtml()
    {
        $this->setTemplate('authorizenetcim/sales/order/invoice/create/items.phtml');
        parent::_beforeToHtml();
    }

    /**
     * Get is show select for capture or not
     *
     * @return boolean
     */
    public function isShowPartialSelect() {
        if (Mage::getModel('authorizenetcim/profile')->isAuthForPartialCapture() == Gorilla_AuthorizenetCim_Model_Gateway::PARTIAL_CAPTURE_FULL_AMOUNT ||
            Mage::getModel('authorizenetcim/profile')->isAuthForPartialCapture() == Gorilla_AuthorizenetCim_Model_Gateway::PARTIAL_CAPTURE_REMAINING_BALANCE) {
            if ($this->canCapture() && Mage::getSingleton('adminhtml/session_quote')->getIsShowPartialSelect())
                return true;
        }
        return false;
    }

    public function getUpdateQtyOnClick() {
        $onclick = "submitAndReloadArea($('invoice_item_container'),'".$this->getUpdateUrl()."')";
        return $onclick;
    }

}