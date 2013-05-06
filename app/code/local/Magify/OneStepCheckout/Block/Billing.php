<?php
/**
 *    OneStepCheckout main block
 *    @author Eric Czar <info@magify.com>
 *    @copyright Eric Czar <info@magify.com>
 *
 */

class Magify_OneStepCheckout_Block_Billing extends Mage_Checkout_Block_Onepage_Abstract    {

    var $formErrors;
    var $settings;
    var $log = array();

    public function __construct()
    {
        $this->settings = Mage::helper('onestepcheckout/checkout')->loadConfig();
    }
}