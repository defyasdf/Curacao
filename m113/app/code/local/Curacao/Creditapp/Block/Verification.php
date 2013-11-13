<?php

class Curacao_Creditapp_Block_Verification extends Curacao_Creditapp_Block_Abstract {

    /**
     * Initialize verification step
     *
     */
    protected function _construct() {
        $this->getCheckout()->setStepData('verification', array(
            'label' => $this->__('Data Verification'),
            'is_show' => $this->isShow()
        ));
        parent::_construct();
    }

}