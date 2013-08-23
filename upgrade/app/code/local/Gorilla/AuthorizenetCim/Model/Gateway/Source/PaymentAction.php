<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Model_Gateway_Source_PaymentAction
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => Gorilla_AuthorizenetCim_Model_Gateway::ACTION_AUTHORIZE,
                'label' => Mage::helper('paygate')->__('Authorize Only')
            ),
            array(
                'value' => Gorilla_AuthorizenetCim_Model_Gateway::ACTION_AUTHORIZE_CAPTURE,
                'label' => Mage::helper('paygate')->__('Authorize and Capture')
            ),
        );
    }
}