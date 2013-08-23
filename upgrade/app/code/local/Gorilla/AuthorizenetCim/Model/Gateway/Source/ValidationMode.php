<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Model_Gateway_Source_ValidationMode
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => Gorilla_AuthorizenetCim_Model_Gateway::VALIDATION_MODE_NONE,
                'label' => Mage::helper('authorizenetcim')->__('None')
            ),
            array(
                'value' => Gorilla_AuthorizenetCim_Model_Gateway::VALIDATION_MODE_TEST,
                'label' => Mage::helper('authorizenetcim')->__('Test')
            ),
            array(
                'value' => Gorilla_AuthorizenetCim_Model_Gateway::VALIDATION_MODE_LIVE,
                'label' => Mage::helper('authorizenetcim')->__('Live')
            ),
        );
    }
}