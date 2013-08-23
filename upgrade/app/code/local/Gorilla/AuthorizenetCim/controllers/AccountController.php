<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_AccountController extends Mage_Core_Controller_Front_Action
{
    /**
     * Only logged in users can use this functionality,
     * this function checks if user is logged in before all other actions
     *
     */
    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
    }
    
    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }
    
    public function cardsAction()
    {        
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->loadLayoutUpdates();
        
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle($this->__('My Credit Cards'));
        }

        $this->renderLayout();    
    }
    
    public function addAction()
    {                
        $data = $this->_getSession()->getCustomerCardFormData(true);       
        Mage::register('card_form_data',$data);        
        
        $msg = $this->_getSession()->getMessages(true);              
        $this->loadLayout();
        $this->getLayout()->getMessagesBlock()->addMessages($msg);             
        $this->_initLayoutMessages('customer/session');             
        
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle($this->__('Add a Credit Card'));
        }
        
        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('authorizenetcim/account/cards');
        }
        
        $this->getLayout()->getBlock('cards_form')->setCimMode('Add');
        $this->getLayout()->getBlock('cards_form')->setMyAccountHeader('Add a Credit Card');
        
        $this->renderLayout();    
    }
    
    public function editAction()
    {                       
        $session = $this->_getSession();
        $session->setEscapeMessages(true); // prevent XSS injection in user input
        $token_id = $this->getRequest()->getParam('id', false);
        $paymentProfile = Mage::getModel('authorizenetcim/profile')
                ->getCustomerPaymentProfile($session->getCustomer()->getCimGatewayId(), $token_id);        
        
        if (!$paymentProfile) {
            $session->addError($this->__('Invalid payment profile requested.'));
            $this->_redirectError(Mage::getUrl('*/*/cards', array('_secure' => true)));
            return;
        }
                
        $form_data = $this->_prepareGatewayDataForForm($paymentProfile);
        Mage::register('card_form_data',$form_data);        
        
        $msg = $this->_getSession()->getMessages(true);              
        $this->loadLayout();
        $this->getLayout()->getMessagesBlock()->addMessages($msg);             
        $this->_initLayoutMessages('customer/session');             
        
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle($this->__('Update Credit Card'));
        }
        
        if ($navigationBlock = $this->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('authorizenetcim/account/cards');
        }
        
        $formBlock = $this->getLayout()->getBlock('cards_form');
        $formBlock->setCimMode('Edit')->setMyAccountHeader('Edit a Credit Card')->setCcGatewayId($token_id);        
        
        $this->renderLayout();   
    }
    
    public function deleteAction()
    {
        $session = $this->_getSession();
        $session->setEscapeMessages(true); // prevent XSS injection in user input
        
        $customerProfileId = Mage::getModel('customer/session')->getCustomer()->getCimGatewayId();
        $customerPaymentProfileId = $this->getRequest()->getParam('id', false);
        
        // Make sure this is a valid profile, we don't want customers deleting
        // other peoples' info just by knowing the Id
        $paymentProfile = Mage::getModel('authorizenetcim/profile')
                ->getCustomerPaymentProfile($customerProfileId, $customerPaymentProfileId);        
        
        if (!$paymentProfile) {
            $session->addError($this->__('Invalid payment profile requested.'));
            $this->_redirectError(Mage::getUrl('*/*/cards', array('_secure' => true)));
            return;
        }
        
        // Check to see if we can delete the card
        if (!$this->_canDeletePaymentProfile($customerPaymentProfileId))
        {
            $session->addError($this->__('The card you requested to delete is currently in use on one or more order. Please try again once your orders have been completed.'));
            $this->_redirectError(Mage::getUrl('*/*/cards', array('_secure' => true)));
            return;
        }
        
        // Perform the Delete
        $result = Mage::getModel('authorizenetcim/profile')->deleteCustomerPaymentProfile($customerProfileId, $customerPaymentProfileId);        
        if ($result) {
            $session->addSuccess($this->__('Card successfully deleted.'));
            $this->_redirectError(Mage::getUrl('*/*/cards', array('_secure' => true)));
            return;
        } else {
            $session->addError($this->__('Unable to delete card at this time.'));
            $this->_redirectError(Mage::getUrl('*/*/cards', array('_secure' => true)));
            return;
        }
        
    }
    
    /**
     * Save CC data
     */
    public function saveAction()
    {
        $session = $this->_getSession();
        $session->setEscapeMessages(true); // prevent XSS injection in user input
        $data = $this->getRequest()->getPost('payment');
        
        $cim_profile_model = Mage::getModel('authorizenetcim/profile');
        $card_customer = $this->_prepareCustomerForGateway($data);   
        
        // If this is a new profile entirely, create the customer and card at 
        // the same time.
        if (!$card_customer->getGatewayId())
        {
            // create the customer profile and payment profile
            $cim_profile = $cim_profile_model->createCustomerProfile($card_customer, true);            
            
            // If we didn't get a profile ID, then set the error and redirect the customer
            if (!$cim_profile) {
                foreach ($cim_profile_model->getErrorMessages() as $error_message)
                {
                    $this->_getSession()->addError($error_message);
                }
                
                $this->_getSession()->setCustomerCardFormData($this->getRequest()->getPost());                
                $this->_redirectError(Mage::getUrl('*/*/add', array('_secure' => true)));
                return;
            } else {
                $card_customer->setGatewayId($cim_profile->getCustomerProfileId());   
                
                // save profile id on customer record
                $customer = $this->_getSession()->getCustomer();
                Mage::getModel('authorizenetcim/profile')->setCustomerId($customer->getId())->setGatewayId($cim_profile->getCustomerProfileId())->setDefaultPaymentId($cim_profile->getCustomerPaymentProfileId())->save();
                
                $session->addSuccess($this->__('Card successfully saved.'));
                $this->_redirectSuccess(Mage::getUrl('*/*/cards', array('_secure' => true)));
                return;
            }
            
        }
        
        // If this is just a card add/update do the following
        if ($card_customer->getPaymentProfile()->getGatewayId())
        {
            $result = $cim_profile_model->updateCustomerPaymentProfile($card_customer);
            if ($result) {
                if (isset($data['cc_default_card']) && $data['cc_default_card']) {
                    Mage::getModel('authorizenetcim/profile')->load($this->_getSession()->getCustomer()->getCimProfileId())->setDefaultPaymentId($data['cc_gateway_id'])->save();
                }
                
                $session->addSuccess($this->__('Card successfully saved.'));
                $this->_redirectSuccess(Mage::getUrl('*/*/cards', array('_secure' => true)));
            } else {
                foreach ($cim_profile_model->getErrorMessages() as $error_message) {
                    $this->_getSession()->addError($error_message);
                }
                $this->_redirectError(Mage::getUrl('*/*/edit/id/' . $card_customer->getPaymentProfile()->getGatewayId(), array('_secure' => true)));
                return;
            }
            
            
        } else { // Add new CC to CIM
            $payment_profile_id = $cim_profile_model->createCustomerPaymentProfile($card_customer);
            if (!$payment_profile_id) {
                foreach ($cim_profile_model->getErrorMessages() as $error_message)
                {
                    $this->_getSession()->addError($error_message);
                }
                $this->_getSession()->setCustomerCardFormData($this->getRequest()->getPost());                
                $this->_redirectError(Mage::getUrl('*/*/add/', array('_secure' => true)));
                return;
            } else {
                $session->addSuccess($this->__('Card successfully saved.'));

                if (isset($data['cc_default_card']) && $data['cc_default_card']) {
                    Mage::getModel('authorizenetcim/profile')->load($this->_getSession()->getCustomer()->getCimProfileId())->setDefaultPaymentId($payment_profile_id)->save();
                }
                
                $this->_redirectSuccess(Mage::getUrl('*/*/cards', array('_secure' => true)));
                return;
            }
        }
        
    }
    
    /**
     * Format the data properly for the Gateway post
     * 
     * @param type $customer_data
     * @return Varien_Object $customer
     */
    public function _prepareCustomerForGateway($customer_data)
    {
        
        $state = '';
        if (isset($customer_data['cc_billing_state_id']) && !empty($customer_data['cc_billing_state_id'])) {
            $state = $this->_getStateById($customer_data['cc_billing_state_id']);
        } else {
            $state = $customer_data['cc_billing_state'];
        }
        
        $customer = new Varien_Object;
        $customer->setEmail(Mage::getModel('customer/session')->getCustomer()->getEmail())
                ->setId(Mage::getModel('customer/session')->getCustomer()->getId())
                ->setDescription('Magento Customer ID: ' . Mage::getModel('customer/session')->getCustomer()->getId())
                ->setGatewayId(Mage::getModel('customer/session')->getCustomer()->getCimGatewayId())
                ->setFirstname($customer_data['cc_first_name'])
                ->setLastname($customer_data['cc_last_name'])
                ->setCompany($customer_data['cc_company'])
                ->setAddress($customer_data['cc_billing_address'])
                ->setCity($customer_data['cc_billing_city'])
                ->setState($state)
                ->setZip($customer_data['cc_billing_zip'])
                ->setCountry($customer_data['cc_country_id']);
        
        $payment_profile = new Varien_Object;
        $customer->setPaymentProfile($payment_profile);
        
        $formatted_expiration = '';
        if ($customer_data['cc_exp_year'] == "XX" && $customer_data['cc_exp_month'] = "XX")
        {
            $formatted_expiration = $customer_data['cc_exp_year'] . $customer_data['cc_exp_month'];
        } else {
            $formatted_expiration = $customer_data['cc_exp_year'] . "-" . $customer_data['cc_exp_month'];
        }
        
        $customer->getPaymentProfile()
                ->setCc($customer_data['cc_number'])
                ->setExpiration($formatted_expiration)
                ->setCcv($customer_data['cc_cid'])
                ->setGatewayId($customer_data['cc_gateway_id']);

        return $customer;
    }
    
    public function _prepareGatewayDataForForm($paymentProfile)
    {
        $customer_data = array(
            'payment' => array(
                'cc_first_name' => $paymentProfile->billTo->firstName,
                'cc_last_name' => $paymentProfile->billTo->lastName,
                'cc_company' => $paymentProfile->billTo->company,
                'cc_billing_address' => $paymentProfile->billTo->address,
                'cc_billing_city' => $paymentProfile->billTo->city,
                'cc_billing_state' => $paymentProfile->billTo->state,
                'cc_billing_zip' => $paymentProfile->billTo->zip,
                'cc_country_id' => $paymentProfile->billTo->country,
                'cc_number' => $paymentProfile->payment->creditCard->cardNumber,
            )
        );        
        
        return $customer_data;
    }
    
    /**
     * Check to make sure the customer has no open orders before deleting the
     * payment profile.
     * 
     * @param type $payment_profile_id
     * @return bool 
     */
    protected function _canDeletePaymentProfile($payment_profile_id) 
    {
        $ordersCollection = Mage::getModel('sales/order')->getCollection()
                ->addFieldToFilter('customer_id', array('eq' => $this->_getSession()->getCustomer()->getId()))
                ->addFieldToFilter('status', array('nin' => array('complete','canceled')));

        foreach ($ordersCollection as $order) {
            if ($order->getPayment()->getAuthorizenetcimPaymentId() == $payment_profile_id) {
                return false;
                break;
            }
        }
        
        return true;
    }
    
    protected function _getStateById($id)
    {
        return Mage::getModel('directory/region')->load($id)->getCode();
    }
}