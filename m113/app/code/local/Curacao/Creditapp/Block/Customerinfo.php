<?php

class Curacao_Creditapp_Block_Customerinfo extends Curacao_Creditapp_Block_Abstract {

    /**
     * Initialize CustomerInfo step
     */
    protected function _construct() {
        $this->getCheckout()->setStepData('customerinfo', array(
            'label' => Mage::helper('checkout')->__('Personal Information'),
            'is_show' => $this->isShow()
        ));
        $this->getCheckout()->setStepData('customerinfo', 'allow', true);
        parent::_construct();
    }

}