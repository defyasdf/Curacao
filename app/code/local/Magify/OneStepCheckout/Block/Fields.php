<?php
class Magify_OneStepCheckout_Block_Fields extends Magify_OneStepCheckout_Block_Checkout
{
    public function _construct(){
        $this->setSubTemplate(true);
        parent::_construct();
    }
}