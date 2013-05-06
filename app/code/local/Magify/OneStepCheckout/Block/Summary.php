<?php
/**
 *    OneStepCheckout summary block
 *    @author Eric Czar <info@magify.com>
 *    @copyright Eric Czar <info@magify.com>
 *
 */

class Magify_OneStepCheckout_Block_Summary extends Mage_Checkout_Block_Cart_Totals {

    public function __construct()
    {
        $this->getQuote()->collectTotals()->save();
    }

    public function getItems()
    {
        return $this->getQuote()->getAllVisibleItems();
    }

    public function getTotals()
    {
        return $this->getQuote()->getTotals();
    }

    public function getGrandTotal(){
        return $this->getQuote()->getGrandTotal();
    }
}
