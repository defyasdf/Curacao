<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Model_Gateway_Source_PartialCapture
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => Gorilla_AuthorizenetCim_Model_Gateway::PARTIAL_CAPTURE_NOT_ADDITIONAL,
                'label' => Mage::helper('paygate')->__('None')
            ),
            array(
                'value' => Gorilla_AuthorizenetCim_Model_Gateway::PARTIAL_CAPTURE_FULL_AMOUNT,
                'label' => Mage::helper('paygate')->__('Full amount')
            ),
            array(
                'value' => Gorilla_AuthorizenetCim_Model_Gateway::PARTIAL_CAPTURE_REMAINING_BALANCE,
                'label' => Mage::helper('paygate')->__('Remaining balance')
            ),

        );
    }
}