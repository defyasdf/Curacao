<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Model_Gateway_Source_Cctype extends Mage_Payment_Model_Source_Cctype
{
    /**
     * Return allowed cc types for current method
     *
     * @return array
     */
    public function getAllowedTypes()
    {
        return array('VI', 'MC', 'AE', 'DI', 'OT');
    }
}