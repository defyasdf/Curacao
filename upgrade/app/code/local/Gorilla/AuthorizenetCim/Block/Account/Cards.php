<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Block_Account_Cards extends Mage_Customer_Block_Account_Dashboard
{
    /**
     * Get a list of credit cards for the account
     * 
     * @return array $cards|bool 
     */
    public function getCards()
    {        
        if (!$this->getData('cards'))
        {
            $cards = array(); // Array to hold card objects
            
            $customer = Mage::getModel('customer/session')->getCustomer();
            if (!$customer->getCimGatewayId())
            {
                return false;
            }

            $cim_profile = Mage::getModel('authorizenetcim/profile')->getCustomerProfile($customer->getCimGatewayId());
            if ($cim_profile)
            {               
                if (isset($cim_profile->paymentProfiles) && is_object($cim_profile->paymentProfiles))
                {
                    /**
                     * The Soap XML response may be a single stdClass or it may be an
                     * array. We need to adjust it to make it uniform. 
                     */            
                    if (is_array($cim_profile->paymentProfiles->CustomerPaymentProfileMaskedType))
                    {
                        $payment_profiles = $cim_profile->paymentProfiles->CustomerPaymentProfileMaskedType;
                    } else {
                        $payment_profiles = array($cim_profile->paymentProfiles->CustomerPaymentProfileMaskedType);
                    }

                    // Assign card objects to array
                    foreach ($payment_profiles as $payment_profile)
                    {
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

            }
            
            if (!empty($cards))
            {
                $this->setData('cards', $cards);
            } else {            
                $this->setData('cards',false);
            }
        }
        
        return $this->getData('cards');
        
    }
    
    public function getEditUrl($card)
    {
        return Mage::getUrl('*/*/edit/id/' . $card->getGatewayId(), array('_secure' => true));
    }
    
    public function getDeleteUrl()
    {
        return Mage::getUrl('*/*/delete/', array('_secure' => true));
    }
}