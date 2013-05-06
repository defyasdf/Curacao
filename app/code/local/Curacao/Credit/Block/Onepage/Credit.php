<?php

class Curacao_Credit_Block_Onepage_Credit extends Mage_Checkout_Block_Onepage_Abstract
{
    protected function _construct()
    {    	
        $this->getCheckout()->setStepData('credit', array(
            'label'     => Mage::helper('checkout')->__('Curacao Credit'),
            'is_show'   => true
        ));
        
        parent::_construct();
    }
}