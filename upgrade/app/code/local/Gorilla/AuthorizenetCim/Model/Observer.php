<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Model_Observer //extends Mage_Core_Model_Observer
{
    /**
     * Event: customer_delete_before
     * 
     * @param type $observer 
     */
    public function deleteCustomer($observer)
    {
        $cim_id = $observer->getCustomer()->getCimGatewayId();        
        Mage::getModel('authorizenetcim/profile')->deleteCustomer($cim_id);
    }
    
    /**
     * Event: customer_load_after
     * 
     * Attach the customer's CIM id to the customer profile when loaded
     * 
     * @param type $observer
     * @return Gorilla_AuthorizenetCim_Model_Observer 
     */
    public function loadCimId($observer)
    {
        $customer = $observer->getEvent()->getCustomer();        
        Mage::getModel('authorizenetcim/profile')->loadGatewayIdByCustomer($customer);        
        return $this;
    }
    
    /**
     * Checkout Success, check for Session stuff from CIM. Save info to profile
     * if necessary.
     */
    public function saveCimDataPostOrder($observer)
    {
        $order = $observer->getOrder();
        $payment = $order->getPayment();
        
        //Check and see if saving is optional
        $save_optional = $payment->getMethodInstance()->getConfigData('save_optional');
        
        if (($payment->getAdditionalInformation('cc_save_card') ||  !$save_optional) && $payment->getAuthorizenetcimCustomerId() && $order->getCustomerId())
        {
            $customer = Mage::getModel('customer/customer')->load($order->getCustomerId());
            if ($customer && !$customer->getCimGatewayId() && $payment->getAuthorizenetcimCustomerId() && !is_null($payment->getAuthorizenetcimCustomerId()))
            {
                Mage::getModel('authorizenetcim/profile')->setCustomerId($customer->getId())->setGatewayId($payment->getAuthorizenetcimCustomerId())->save();
            }
        }        
                
        return $this;
        
    }    
    
    /**
     * Event: checkout_submit_all_after
     * 
     * Cleanup session data
     */
    public function cleanUpCheckoutSession()
    {
        Mage::getSingleton('checkout/session')
                ->unsAuthorizenetcimCustomerId()
                ->unsAuthorizenetCimPaymentId();
    }
    
    /**
     * Event: sales_convert_order_to_quote
     * 
     * Converting an order to a quote brings over payment information that we 
     * really don't want to copy to a new order. This removes that.
     */
    public function cleanUpPaymentInformation($observer)
    {
        $quote = $observer->getQuote();
        $quote->getPayment()->unsAdditionalInformation();
        return $this;
    }
}