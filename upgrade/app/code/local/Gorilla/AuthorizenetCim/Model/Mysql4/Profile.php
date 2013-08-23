<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Model_Mysql4_Profile extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('authorizenetcim/profile','profile_id');
    }
    
    /**
     * Fetch dealer IDs for a given customer and attach to the customer object
     * 
     * @param type $customer 
     */
    public function loadGatewayIdByCustomer($customer)
    {
        $read = $this->_getReadAdapter();
        if ($read)
        {
            $select = $read->select()
                    ->from($this->getMainTable(),array('profile_id','gateway_id','default_payment_id'))
                    ->where('customer_id = ?', $customer->getId());
                        
            $data = $read->fetchRow($select);
            if ($data)
            {                               
                $customer->setCimGatewayId($data['gateway_id'])->setCimDefaultToken($data['default_payment_id'])->setCimProfileId($data['profile_id']);
            }
        }
        
        return $this;
    }
}