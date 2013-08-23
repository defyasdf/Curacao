<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Block_Form_Cc extends Mage_Payment_Block_Form_Cc
{
    /**
     * Prepare the form template
     */
    public function _prepareLayout()
    {
        $this->setTemplate('authorizenetcim/form/cc.phtml');
    }
    
    /**
     * Check to see if we're inside the admin panel
     * 
     * @return bool
     */
    public function isAdmin()
    {
        if (Mage::app()->getStore()->isAdmin())
        {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Retrieve the customer for this quote
     * 
     * @return Mage_Customer_Model_Customer 
     */
    protected function getCustomer()
    {
        if($this->isAdmin()) {
            return Mage::getModel('customer/customer')->load(Mage::getSingleton('adminhtml/session_quote')->getCustomerId()); // Get customer from admin panel quote
        } else {
            return Mage::getModel('customer/session')->getCustomer(); // Get customer from frontend quote
        }
    }
    
    /**
     * Logged in check
     * 
     * @return bool
     */
    public function isLoggedIn()
    {
        if (!$this->isAdmin()) {
            if (Mage::helper('customer')->isLoggedIn()) {
                return true;
            }            
            
            if (Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getSaveInAddressBook()) {
                return true;
            }
            
            return false;
            
        } else {
            return true; // If this is the admin panel, we just assume we're logged in
        }
        
    }    
    
    /**
     * Check to see if saving the CC is optional or not
     * 
     * @return bool 
     */
    public function isSaveOptional()
    {
        if ($this->getMethod()) 
        {
            $configData = $this->getMethod()->getConfigData('save_optional');
            return $configData;
        }
        return false;
    }
    
    /**
     * Determine if this is a guest checkout
     */
    public function isGuest()
    {
        if (Mage::getSingleton('checkout/session')->getQuote()->getCheckoutMethod() == Mage_Checkout_Model_Type_Onepage::METHOD_GUEST) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Get a list of stored credit cards
     * 
     * @return array $cards | bool 
     */
    public function getStoredCards()
    {
        if (!$this->getData('stored_cards')) {
            $cards = array(); // Array to hold card objects
            
            $customer = $this->getCustomer();
            $cimGatewayId = $customer->getCimGatewayId();

            if (!$cimGatewayId) {
                if($this->isAdmin() && Mage::getModel('authorizenetcim/profile')->isAllowGuestCimProfile()) {
                    $orderId = Mage::getSingleton('adminhtml/session_quote')->getOrderId();
                    if (!$orderId) $orderId = Mage::getSingleton('adminhtml/session_quote')->getReordered();
                    if (!$orderId) return false;
                    $order = Mage::getModel('sales/order')->load($orderId);
                    $payment = $order->getPayment();
                    if ($payment) {
                        $cimCustomerId = $payment->getData('authorizenetcim_customer_id');
                        $cimPaymentId = $payment->getData('authorizenetcim_payment_id');
                    }
                } else {
                    return false;
                }
            }

            if ($cimGatewayId) {
                $cim_profile = Mage::getModel('authorizenetcim/profile')->getCustomerProfile($cimGatewayId);
                if ($cim_profile) {
                    if (isset($cim_profile->paymentProfiles) && is_object($cim_profile->paymentProfiles)) {
                        /**
                         * The Soap XML response may be a single stdClass or it may be an
                         * array. We need to adjust it to make it uniform.
                         */
                        if (is_array($cim_profile->paymentProfiles->CustomerPaymentProfileMaskedType)) {
                            $payment_profiles = $cim_profile->paymentProfiles->CustomerPaymentProfileMaskedType;
                        } else {
                            $payment_profiles = array($cim_profile->paymentProfiles->CustomerPaymentProfileMaskedType);
                        }
                    }
                }
            } else {
                if ($cimCustomerId && $cimPaymentId) {
                    $payment_profiles = array(Mage::getModel('authorizenetcim/profile')->getCustomerPaymentProfile($cimCustomerId, $cimPaymentId));
                } else {
                    return false;
                }
            }

            if (isset($payment_profiles) && $payment_profiles) {
                // Assign card objects to array
                foreach ($payment_profiles as $payment_profile) {
                    $card = new Varien_Object();
                    $card->setCcNumber($payment_profile->payment->creditCard->cardNumber)
                        ->setGatewayId($payment_profile->customerPaymentProfileId)
                        ->setFirstname($payment_profile->billTo->firstName)
                        ->setLastname($payment_profile->billTo->lastName)
                        ->setAddress($payment_profile->billTo->address)
                        ->setCity($payment_profile->billTo->city)
                        ->setState($payment_profile->billTo->state)
                        ->setZip($payment_profile->billTo->zip)
                        ->setCountry($payment_profile->billTo->country);
                    $cards[] = $card;
                }
            }
            
            if (!empty($cards)) {
                $this->setData('stored_cards', $cards);
            } else {            
                $this->setData('stored_cards',false);
            }
        }
        
        return $this->getData('stored_cards');
    }
}