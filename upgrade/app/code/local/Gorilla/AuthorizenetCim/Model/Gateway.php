<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Model_Gateway extends Mage_Payment_Model_Method_Cc
{
    const PARTIAL_CAPTURE_FULL_AMOUNT  = 'partial_capture_full_amont';
    const PARTIAL_CAPTURE_REMAINING_BALANCE = 'partial_capture_remaining_balance';
    const PARTIAL_CAPTURE_NOT_ADDITIONAL = 'partial_capture_not_additional';

    // Authorize.net Response Codes
    const RESPONSE_CODE_APPROVED = 1;
    const RESPONSE_CODE_DECLINED = 2;
    const RESPONSE_CODE_ERROR    = 3;
    const RESPONSE_CODE_HELD     = 4;

    // Validation Modes
    const VALIDATION_MODE_NONE = 'none'; // Just validates field content
    const VALIDATION_MODE_TEST = 'testMode'; // A mock $1.00 transaction performed
    const VALIDATION_MODE_LIVE = 'liveMode'; // Generates a $0 transaction to the customer's card
    
    // Delimiter Character for DirectResponse
    const RESPONSE_DELIM_CHAR = '(~)';
    
    // Reason Codes
    const RESPONSE_REASON_CODE_APPROVED = 1;
    const RESPONSE_REASON_CODE_PARTIAL_APPROVE = 295;
    const RESPONSE_REASON_CODE_ALREADY_VOIDED = 310;
    
    // Method Code for this Magento payment Method
    const METHOD_CODE = 'authorizenetcim';       
    
    // Scenario types for customers
    const SCENARIO_CUSTOMER_STORED_COMPLETE = 'CustomerCompleteCimProfile';
    const SCENARIO_CUSTOMER_ADD_CARD = 'CustomerAddCimCard';
    const SCENARIO_CUSTOMER_ADD_PROFILE = 'CustomerAddCimProfile';
    const SCENARIO_REGISTRANT_ADD_PROFILE = 'RegistrationAddCimProfile';
    const SCENARIO_NO_SAVE = 'SingleUseCimProfile';
    
    /**
    * unique internal payment method identifier
    *
    * @var string [a-z0-9_]
    */
    protected $_code = self::METHOD_CODE;    
    
    /**
     * The Block type for the Payment Form
     * 
     * @var string
     */
    protected $_formBlockType = 'authorizenetcim/form_cc';
    
    /**
     * The Block type for the Payment Info
     * 
     * @var string
     */
    protected $_infoBlockType = 'authorizenetcim/info_cc';
     
    /**
     * Key for storing transaction id in additional information of payment model
     * @var string
     */
    protected $_realTransactionIdKey = 'real_transaction_id';
    
    /**
     * Key for storing locking gateway actions flag in additional information of payment model
     * @var string
     */
    protected $_isGatewayActionsLockedKey = 'is_gateway_actions_locked';
    
    /**
     * Is this payment method a gateway (online auth/charge) ?
     */
    protected $_isGateway               = true;
 
    /**
     * Can authorize online?
     */
    protected $_canAuthorize            = true;
 
    /**
     * Can capture funds online?
     */
    protected $_canCapture              = true;
 
    /**
     * Can capture partial amounts online?
     */
    protected $_canCapturePartial       = true;
 
    /**
     * Can refund online?
     */
    protected $_canRefund               = true;
 
    /**
     * Can void transactions online?
     */
    protected $_canVoid                 = true;
 
    /**
     * Can use this payment method in administration panel?
     */
    protected $_canUseInternal          = true;
 
    /**
     * Can show this payment method as an option on checkout payment page?
     */
    protected $_canUseCheckout          = true;
 
    /**
     * Is this payment method suitable for multi-shipping checkout?
     */
    protected $_canUseForMultishipping  = true;
 
    /**
     * Is this payment method able to partially refund an invoice?
     */
    protected $_canRefundInvoicePartial = true;
    
    /**
     * Can save credit card information for future processing?
     */
    protected $_canSaveCc = false;
    
    /**
     * What currencies may be used with this gateway?
     */
    protected $_allowCurrencyCode = array('USD');
    
    /**
     * Get the code for this payment method
     * 
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }
    
    /**
     * Check method for processing with base currency
     *
     * @param string $currencyCode
     * @return boolean
     */
    public function canUseForCurrency($currencyCode)
    {
        if (!in_array($currencyCode, $this->getAcceptedCurrencyCodes())) {
            return false;
        }
        return true;
    }

    /**
     * Return array of currency codes supplied by Payment Gateway
     *
     * @return array
     */
    public function getAcceptedCurrencyCodes()
    {
        if (!$this->hasData('_accepted_currency')) {
            $acceptedCurrencyCodes = $this->_allowCurrencyCode;
            $acceptedCurrencyCodes[] = $this->getConfigData('currency');
            $this->setData('_accepted_currency', $acceptedCurrencyCodes);
        }
        return $this->_getData('_accepted_currency');
    }
    
    /**
     * Validate the provided payment information - happens after customer clicks
     * next from payment section of checkout.
     * 
     * @return Gorilla_AuthorizenetCim_Model_Gateway 
     */
    public function validate()
    {
        $paymentInfo = $this->getInfoInstance();       
        
        $stored = false;
        if ($paymentInfo->getAdditionalInformation('authorizenetcim_customer_id') && $paymentInfo->getAdditionalInformation('authorizenetcim_payment_id')) {
            $stored = true;
        }  elseif($this->isAdmin() && !$this->getCustomer()->getId() && $paymentInfo->getAuthorizenetcimPaymentId()) {
            if (Mage::getModel('authorizenetcim/profile')->isAllowGuestCimProfile()) {
                $stored = true;
            }
        }
        // If it's a stored card it's already been validated, so just make sure
        // this payment method is still available.
        if ($stored) {
             if ($paymentInfo instanceof Mage_Sales_Model_Order_Payment) {
                 $billingCountry = $paymentInfo->getOrder()->getBillingAddress()->getCountryId();
             } else {
                 $billingCountry = $paymentInfo->getQuote()->getBillingAddress()->getCountryId();
             }

             $paymentInfo = $this->getInfoInstance();
             if ($paymentInfo instanceof Mage_Sales_Model_Order_Payment) {
                 $billingCountry = $paymentInfo->getOrder()->getBillingAddress()->getCountryId();
             } else {
                 $billingCountry = $paymentInfo->getQuote()->getBillingAddress()->getCountryId();
             }
             if (!$this->canUseForCountry($billingCountry)) {
                 Mage::throwException($this->_getHelper()->__('Selected payment type is not allowed for billing country.'));
             }
             return $this;
        } else { // Validate a new card
             return parent::validate();             
        }
    }   
    
    /**
     * Send authorize request to gateway
     *
     * @param  Mage_Payment_Model_Info $payment
     * @param  decimal $amount
     * @return Gorilla_AuthorizenetCim_Model_Gateway
     */
    public function authorize(Varien_Object $payment, $amount)
    {         
        if ($amount <= 0) {
            Mage::throwException(Mage::helper('authorizenetcim')->__('Invalid amount for authorization.'));
        }
        
        $this->_initCardsStorage($payment);
                               
        $this->_place($payment, $amount, Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_ONLY);
        $payment->setSkipTransactionCreation(true);
        
        return $this;
    }
    
    /**
     * Send capture request to gateway
     *
     * @param Mage_Payment_Model_Info $payment
     * @param decimal $amount
     * @return Mage_Paygate_Model_Authorizenet
     */
    public function capture(Varien_Object $payment, $amount)
    {
        if ($amount <= 0) {
            Mage::throwException(Mage::helper('authorizenetcim')->__('Invalid amount for capture.'));
        }
        
        $this->_initCardsStorage($payment);
        if ($this->_isPreauthorizeCapture($payment)) {
            if (Mage::getModel('authorizenetcim/profile')->isAuthForPartialCapture() == Gorilla_AuthorizenetCim_Model_Gateway::PARTIAL_CAPTURE_NOT_ADDITIONAL ||
                ( Mage::app()->getRequest()->getParam('do_pre_auth_capture') && Mage::app()->getRequest()->getParam('do_pre_auth_capture') == 'none' )){
                $this->_preauthorizeCapture($payment, $amount);
            } elseif (!Mage::app()->getRequest()->getParam('do_pre_auth_capture') ||
                      ( Mage::app()->getRequest()->getParam('do_pre_auth_capture') && Mage::app()->getRequest()->getParam('do_pre_auth_capture') == 'partial' )) {
                $this->_preauthorizeCaptureWithAuthorizeBefore($payment, $amount, Mage::getModel('authorizenetcim/profile')->isAuthForPartialCapture());
            }

        } else {
            $this->_place($payment, $amount, Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_CAPTURE);
        }
        
        $payment->setSkipTransactionCreation(true);
        return $this;
    }
    
    /**
     * Cancel the payment through gateway
     *
     * @param  Mage_Payment_Model_Info $payment
     * @return Gorilla_AuthorizenetCim_Model_Gateway
     */
    public function cancel(Varien_Object $payment)
    {
        return $this->void($payment);
    }
    
    /**
     * Void the payment through gateway
     *
     * @param  Mage_Payment_Model_Info $payment
     * @return Gorilla_AuthorizenetCim_Model_Gateway
     */
    public function void(Varien_Object $payment)
    {        
        $cardsStorage = $this->getCardsStorage($payment);

        $messages = array();
        $isSuccessful = false;
        $isFiled = false;        
        foreach($cardsStorage->getCards() as $card) {            
            try {
                $newTransaction = $this->_voidCardTransaction($payment, $card);
                $messages[] = $newTransaction->getMessage();
                $isSuccessful = true;
            } catch (Exception $e) {
                $messages[] = $e->getMessage();
                $isFiled = true;
                continue;
            }
            $cardsStorage->updateCard($card);
        }

        if ($isFiled) {
            $this->_processFailureMultitransactionAction($payment, $messages, $isSuccessful);
        }

        $payment->setSkipTransactionCreation(true);
        return $this;
    }
    
    /**
     * Refund the amount with transaction id
     *
     * @param Mage_Payment_Model_Info $payment
     * @param decimal $amount
     * @return Gorilla_AuthorizenetCim_Model_Gateway
     * @throws Mage_Core_Exception
     */
    public function refund(Varien_Object $payment, $requestedAmount)
    {
        $cardsStorage = $this->getCardsStorage($payment);

        if ($this->_formatAmount($cardsStorage->getCapturedAmount() - $cardsStorage->getRefundedAmount()) < $requestedAmount) {
            Mage::throwException(Mage::helper('authorizenetcim')->__('Invalid amount for refund.'));
        }

        // check to make sure we have a credit memo invoice
        $credit_memo = Mage::registry('current_creditmemo');
        if (!$credit_memo->getInvoice()->getTransactionId()) {
            Mage::throwException(Mage::helper('authorizenetcim')->__('This invoice does not have a valid transaction ID assigned to it.'));
        }
        
        $messages = array();
        $isSuccessful = false;
        $isFiled = false;
        foreach($cardsStorage->getCards() as $card) {
            if ($requestedAmount > 0) {
                $cardAmountForRefund = $this->_formatAmount($card->getCapturedAmount() - $card->getRefundedAmount());
                if ($cardAmountForRefund <= 0) {
                    continue;
                }
                if ($cardAmountForRefund > $requestedAmount) {
                    $cardAmountForRefund = $requestedAmount;
                }
                
                try {
                    $newTransaction = $this->_refundCardTransaction($payment, $cardAmountForRefund, $card);
                    $messages[] = $newTransaction->getMessage();
                    $isSuccessful = true;
                } catch (Exception $e) {
                    $isFiled = true;
                    Mage::throwException(Mage::helper('authorizenetcim')->__('The Payment Gateway declined the refund. Please ensure that this transaction has been fully settled and is not older than 60 days.'));
                }

                $card->setRefundedAmount($this->_formatAmount($card->getRefundedAmount() + $cardAmountForRefund));
                $cardsStorage->updateCard($card);
                $requestedAmount = $this->_formatAmount($requestedAmount - $cardAmountForRefund);
            } else {
                $payment->setSkipTransactionCreation(true);
                return $this;
            }
        }

        if ($isFiled) {
            $this->_processFailureMultitransactionAction($payment, $messages, $isSuccessful);
        }

        $payment->setSkipTransactionCreation(true);
        return $this;
    }         
    
    /**
     * Send transaction request to the payment gateway. Only used for Auth Only
     * and AuthCapture
     *
     * @param Mage_Payment_Model_Info $payment
     * @param decimal $amount
     * @param string $requestType
     * @return Gorilla_AuthorizenetCim_Model_Gateway
     * @throws Mage_Core_Exception
     */
    protected function _place($payment, $amount, $requestType)
    {     
        $payment->setAnetTransType($requestType);
        $payment->setAmount($amount);
        
        // Handle saving data for a new profile
        $info = $this->getInfoInstance($payment);                    
        
        // Check to see what extra CIM stuff we need to do with this type of transaction
        switch ($payment->getAnetTransType())
        {
            case Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_ONLY:
            case Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_CAPTURE:
                switch ($this->_getAuthorizationScenario($payment)) {
                    case self::SCENARIO_CUSTOMER_STORED_COMPLETE: // Existing customer using stored card and profile or multi-address that's already got its info  
                        $payment->setAuthorizenetcimCustomerId($this->getInfoInstance()->getAdditionalInformation('authorizenetcim_customer_id'))
                                ->setAuthorizenetcimPaymentId($this->getInfoInstance()->getAdditionalInformation('authorizenetcim_payment_id'));                        
                        break;
                    case self::SCENARIO_CUSTOMER_ADD_CARD: // Existing customer w/ CIM profile adding new card           
                        if (!Mage::getSingleton('checkout/session')->getAuthorizenetCimPaymentId()) {
                            // Build the profile request
                            $customer = $this->_buildProfileRequest($payment);

                            // Attempt to get the profile id
                            $paymentProfileId = Mage::getModel('authorizenetcim/profile')->createCustomerPaymentProfile($customer);
                            if (!$paymentProfileId) {
                                Mage::throwException(
                                    Mage::helper('authorizenetcim')->__('Could not create credit card profile (add credit card). Please check your information and try again.')
                                );
                            }
                            
                            // set the payment ID in on the checkout session, this is so that it is available on multi-address checkout
                            Mage::getSingleton('checkout/session')->setAuthorizenetCimPaymentId($paymentProfileId);
                        } else {
                            $paymentProfileId = Mage::getSingleton('checkout/session')->getAuthorizenetCimPaymentId();
                        }
                        
                        // set the payment profile info on the payment object
                        $payment->setAuthorizenetcimCustomerId($this->getInfoInstance()->getAdditionalInformation('authorizenetcim_customer_id'))
                                ->setAuthorizenetcimPaymentId($paymentProfileId);                                                                        
                        break;
                        
                    case self::SCENARIO_CUSTOMER_ADD_PROFILE: // An entirely new CIM profile
                        if (!Mage::getSingleton('checkout/session')->getAuthorizenetcimCustomerId()) {
                            // Build the profile request
                            $customer = $this->_buildProfileRequest($payment);                            

                            // Attempt to get the profile id
                            $customerProfileResponse = Mage::getModel('authorizenetcim/profile')->createCustomerProfile($customer, true);
                            if (!$customerProfileResponse) {
                                Mage::throwException(
                                    Mage::helper('authorizenetcim')->__('Could not create credit card profile (add customer profile). Please check your information and try again.')
                                );
                            } else {
                                $customerProfileId = $customerProfileResponse->getCustomerProfileId();
                                $paymentProfileId = $customerProfileResponse->getCustomerPaymentProfileId();                                
                            }
                            
                            // set the payment ID in on the checkout session, this is so that it is available on multi-address checkout
                            Mage::getSingleton('checkout/session')->setAuthorizenetCimPaymentId($paymentProfileId)->setAuthorizenetcimCustomerId($customerProfileId);
                        } else {
                            $customerProfileId = Mage::getSingleton('checkout/session')->getAuthorizenetcimCustomerId();
                            $paymentProfileId = Mage::getSingleton('checkout/session')->getAuthorizenetCimPaymentId();                            
                        }
                        
                        // set the payment profile info on the payment object
                        $payment->setAuthorizenetcimCustomerId($customerProfileId)
                                ->setAuthorizenetcimPaymentId($paymentProfileId);
                        break;                
                    case self::SCENARIO_NO_SAVE: // One time use CIM Profile for this order
                        // Build the profile request
                        $customer = $this->_buildProfileRequest($payment);                            
                        
                        // Fetch the response
                        $customerProfileResponse = Mage::getModel('authorizenetcim/profile')->createCustomerProfile($customer, true);
                        if (!$customerProfileResponse) {
                            Mage::throwException(
                                Mage::helper('authorizenetcim')->__('The payment gateway could not accept your credit card. Please check your information and try again.')
                            );
                        } else {
                            $customerProfileId = $customerProfileResponse->getCustomerProfileId();
                            $paymentProfileId = $customerProfileResponse->getCustomerPaymentProfileId();                                
                        }
                        
                        // set the payment profile info on the payment object
                        $payment->setAuthorizenetcimCustomerId($customerProfileId)
                                ->setAuthorizenetcimPaymentId($paymentProfileId);   
                        
                        break;
                    default:
                }
                break;
            default:
                //Handle other requests
        }  
                
        // Process the request to the payment gateway
        $result = $this->_postRequest($payment);

        $authorizenetcimTransactionType = $requestType;

        switch ($requestType) 
        {
            case Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_ONLY:
                $newTransactionType = Mage_Sales_Model_Order_Payment_Transaction::TYPE_AUTH;
                $defaultExceptionMessage = Mage::helper('authorizenetcim')->__('Payment authorization error.');
                break;
            case Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_CAPTURE:
                $newTransactionType = Mage_Sales_Model_Order_Payment_Transaction::TYPE_CAPTURE;
                $defaultExceptionMessage = Mage::helper('authorizenetcim')->__('Payment capturing error.');
                break;
        }

        switch ($result->getResponseCode()) {
            case self::RESPONSE_CODE_APPROVED:
                $payment->setCcLast4($result->getCcLast4());
                $payment->setCcType($this->_formatCcType($result->getCardType()));
                $info->setCcLast4($payment->getCcLast4());
                $info->setCcType($payment->getCcType());
                
                $quotePayment = $this->_getSession()->getQuote()->getPayment();
                $quotePayment->setCcLast4($payment->getCcLast4())->setCcType($payment->getCcType());
                
                $card = $this->_registerCard($result, $payment);
                $transaction = $this->_addTransaction(
                        $payment,
                        $card->getLastTransId(),
                        $newTransactionType,
                        array('is_transaction_closed' => 0),
                        array($this->_realTransactionIdKey => $card->getLastTransId()),
                        Mage::helper('authorizenetcim')->getTransactionMessage(
                            $payment, $requestType, $card->getLastTransId(), $card, $amount
                        )
                    );

                //inna
                $transaction->setAuthorizenetcimTxnType($authorizenetcimTransactionType);
                               
                if ($requestType == Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_CAPTURE) {
                    $card->setCapturedAmount($card->getProcessedAmount());
                    $this->getCardsStorage()->updateCard($card);
                }
                return $this;
            case self::RESPONSE_CODE_DECLINED:
            case self::RESPONSE_CODE_ERROR:
                Mage::throwException($this->_wrapGatewayError($result->getResponseReasonText()));
            default:
                Mage::throwException($defaultExceptionMessage);
        }
        return $this;
    }
    
    /**
     * Send capture request to gateway for capture authorized transactions. Re-
     * authorize if necessary.
     *
     * @param Mage_Payment_Model_Info $payment
     * @param decimal $amount
     * @return Mage_Paygate_Model_Authorizenet
     */
    protected function _preauthorizeCapture($payment, $requestedAmount)
    {
        $cardsStorage = $this->getCardsStorage($payment);

        if ($this->_formatAmount($cardsStorage->getProcessedAmount() - $cardsStorage->getCapturedAmount()) < $requestedAmount) {
            Mage::throwException(Mage::helper('paygate')->__('Invalid amount for capture.'));
        }
        $messages = array();
        $isSuccessful = false;
        $isFiled = false;
        foreach($cardsStorage->getCards() as $card) {
            $checkReauth = false;
            if ($requestedAmount > 0) {
                $prevCaptureAmount = $card->getCapturedAmount();
                $cardAmountForCapture = $card->getProcessedAmount();
                if ($cardAmountForCapture > $requestedAmount) {
                    $cardAmountForCapture = $requestedAmount;
                }

                try {
                    $newTransaction = $this->_preauthorizeCaptureCardTransaction($payment, $cardAmountForCapture , $card);
                    $messages[] = $newTransaction->getMessage();
                    $isSuccessful = true;
                    // Perform re-auth if first capture was successful
                } catch (Exception $e) {
                    $messages[] = $e->getMessage();
                    $isFiled = true;
                    continue;
                }
                $newCapturedAmount = $prevCaptureAmount + $cardAmountForCapture;
                $card->setCapturedAmount($newCapturedAmount);
                $cardsStorage->updateCard($card);
                $requestedAmount = $this->_formatAmount($requestedAmount - $cardAmountForCapture);
            } 

            if ($isSuccessful) {
                $balance = $card->getProcessedAmount() - $card->getCapturedAmount();
                if ($balance > 0) {
                    $payment->setAnetTransType(Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_ONLY);
                    $payment->setAmount($balance);
                    $result = $this->_postRequest($payment);                    
                    $response_code = (int) $result->getResponseCode();
                    switch ($response_code) {
                        case self::RESPONSE_CODE_APPROVED:
                            $card->setLastTransId($result->getTransactionId());
                            $this->_addTransaction(
                                $payment,
                                $card->getLastTransId(),
                                Mage_Sales_Model_Order_Payment_Transaction::TYPE_AUTH,
                                array('is_transaction_closed' => 0),
                                array($this->_realTransactionIdKey => $card->getLastTransId()),
                                Mage::helper('authorizenetcim')->getTransactionMessage(
                                    $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_ONLY, $card->getLastTransId(), $card, $balance
                                )
                            );
                            $cardsStorage->updateCard($card);
                            break;
                        case self::RESPONSE_CODE_DECLINED:
                        case self::RESPONSE_CODE_ERROR:
                            $messages[] = 'Re-authorization declined.';
                            break;
                        default:
                            $messages[] = 'Re-authorization gateway error.';
                    }
                    
                }
            }
        }
        if ($isFiled) {
            $this->_processFailureMultitransactionAction($payment, $messages, $isSuccessful);
        }
        return $this;
    }

    /**
     * Send additional authorize request before capture, capture request to gateway for capture authorized transactions. Re-
     * authorize after capture unnecessary.
     *
     * @param Mage_Payment_Model_Info $payment
     * @param decimal $requestedCuptureAmount
     * @param string $authForPartialCaptureType
     * @return Mage_Paygate_Model_Authorizenet
     */
    protected function _preauthorizeCaptureWithAuthorizeBefore($payment, $requestedCuptureAmount, $authForPartialCaptureType) {
        $cardsStorage = $this->getCardsStorage($payment);

        if ($this->_formatAmount($cardsStorage->getProcessedAmount() - $cardsStorage->getCapturedAmount()) < $requestedCuptureAmount) {
            Mage::throwException(Mage::helper('paygate')->__('Invalid amount for capture.'));
        }
        $messages = array();
        $isSuccessfulPreAuthorization = false;
        $isSuccessfulCapture = false;
        $isSuccessfulVoid = false;
        $isFiledPreAuthorization = false;
        $isFiledCapture = false;
        $isFiledVoid = false;

        foreach($cardsStorage->getCards() as $card) {
            if ($requestedCuptureAmount > 0) {
                $prevCaptureAmount = $card->getCapturedAmount();
                $cardAmountForCapture = $card->getProcessedAmount();
                if ($cardAmountForCapture > $requestedCuptureAmount) {
                    $cardAmountForCapture = $requestedCuptureAmount;
                }
                if ($card->getProcessedAmount() - $card->getCapturedAmount() - $requestedCuptureAmount > 0 ) {
                    if ($authForPartialCaptureType == Gorilla_AuthorizenetCim_Model_Gateway::PARTIAL_CAPTURE_FULL_AMOUNT) { // double full amount
                        $preAuthorizeAmount = $card->getProcessedAmount() - $card->getCapturedAmount();
                    } else { //double remaining balance
                        $preAuthorizeAmount = $card->getProcessedAmount() - $card->getCapturedAmount() - $requestedCuptureAmount;
                    }
                } else {
                    $preAuthorizeAmount = 0;
                }

                if ($preAuthorizeAmount > 0) {
                    try {
                        $authTransactionId = $card->getLastTransId();
                        $authTransaction = $payment->getTransaction($authTransactionId);
                        $realAuthTransactionId = $authTransaction->getAdditionalInformation($this->_realTransactionIdKey);

                        $newAuthorizationTransaction = $this->_preauthorizeAuthorizationWithAuthorizeBeforeCardTransaction($payment, $preAuthorizeAmount, $card);
                        $messages[] = $newAuthorizationTransaction->getMessage();
                        $isSuccessfulPreAuthorization = true;
                    } catch (Exception $e) {
                        $messages[] = $e->getMessage();
                        $isFiledPreAuthorization = true;
                        continue;
                    }

                    if ($isSuccessfulPreAuthorization) {
                        if ($cardAmountForCapture > 0) { //capture
                            try {
                                $newCaptureTransaction = $this->_preauthorizeCaptureWithAuthorizeBeforeCardTransaction($payment, $cardAmountForCapture, $card, $authTransactionId);
                                $messages[] = $newCaptureTransaction->getMessage();
                                $isCaptureSuccessful = true;
                            } catch (Exception $e) {
                                $messages[] = $e->getMessage();
                                $isCaptureFiled = true;
                                continue;
                            }
                        }

                        if ($isCaptureSuccessful) {
                            if ($newAuthorizationTransaction->getTxnId()) {
                                $payment->setLastTransId($newAuthorizationTransaction->getTxnId());
                                $card->setLastTransId($newAuthorizationTransaction->getTxnId());
                            }
                            $newCapturedAmount = $prevCaptureAmount + $cardAmountForCapture;
                            $card->setCapturedAmount($newCapturedAmount);
                            $cardsStorage->updateCard($card);
                        } else {
                            //void new authorize trunsaction
                            try {
                                $newVoidTransaction = $this->_preauthorizeVoidWithAuthorizeBeforeCardTransaction($payment, $card, $newAuthorizationTransaction->getTxnId());
                                $messages[] = "Capture was filed. " . $newVoidTransaction->getMessage();
                                $isVoidSuccessful = true;
                            } catch (Exception $e) {
                                $messages[] = $e->getMessage();
                                $isFiledVoid = true;
                                continue;
                            }
                            if ($isVoidSuccessful) {
                                $payment->setLastTransId($authTransactionId);
                                $card->setLastTransId($authTransactionId);
                                $cardsStorage->updateCard($card);
                                $this->_processFailureMultitransactionAction($payment, $messages, $isCaptureSuccessful);
                            }
                        }
                    }
                } else {
                    try {
                        $newTransaction = $this->_preauthorizeCaptureCardTransaction($payment, $cardAmountForCapture , $card);
                        $messages[] = $newTransaction->getMessage();
                        $isSuccessfulCapture = true;
                    } catch (Exception $e) {
                        $messages[] = $e->getMessage();
                        $isFiledCapture = true;
                        continue;
                    }
                    $newCapturedAmount = $prevCaptureAmount + $cardAmountForCapture;
                    $card->setCapturedAmount($newCapturedAmount);
                    $cardsStorage->updateCard($card);
                }
            }
        }

        if ($isFiledPreAuthorization) {
            $this->_getSession()->setIsShowPartialSelect('1');
        } else {
            $this->_getSession()->setIsShowPartialSelect('0');
        }

        if ($isFiledPreAuthorization || $isFiledCapture || $isFiledVoid) {
            $this->_processFailureMultitransactionAction($payment, $messages, $isSuccessfulPreAuthorization);
        }



        return $this;
    }

    // function for additional authorization in partial capture
    /**
     * Send authorization request to gateway for capture authorized transactions of card before capture
     *
     * @param Mage_Payment_Model_Info $payment
     * @param decimal $amount
     * @param Varien_Object $card
     * @return Mage_Sales_Model_Order_Payment_Transaction
     */
    protected function _preauthorizeAuthorizationWithAuthorizeBeforeCardTransaction($payment, $amount, $card)
    {
        $authTransactionId = $card->getLastTransId();
        $authTransaction = $payment->getTransaction($authTransactionId);
        $realAuthTransactionId = $authTransaction->getAdditionalInformation($this->_realTransactionIdKey);

        $payment->setAnetTransType(Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_ONLY);
        $payment->setTransId($realAuthTransactionId);
        $payment->setAmount($amount);

        // Get the result
        $result = $this->_postRequest($payment);

        switch ($result->getResponseCode()) {
            case self::RESPONSE_CODE_APPROVED:
                if ($result->getResponseReasonCode() == self::RESPONSE_REASON_CODE_APPROVED) {
                    $newAuthorizeTransactionId = $result->getTransactionId();
                    $card->setLastTransId($newAuthorizeTransactionId);
                    //$card->setLastCaptureTransId($captureTransactionId);
                    return $this->_addTransaction(
                        $payment,
                        $newAuthorizeTransactionId,
                        Mage_Sales_Model_Order_Payment_Transaction::TYPE_AUTH,
                        array(
                            'is_transaction_closed' => 0,
                            'parent_transaction_id' => $authTransactionId
                        ),
                        array($this->_realTransactionIdKey => $result->getTransactionId()),
                        Mage::helper('authorizenetcim')->getTransactionMessage(
                            $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_ONLY, $result->getTransactionId(), $card, $amount
                        )
                    );
                }
                $exceptionMessage = $this->_wrapGatewayError($result->getResponseReasonText());
                break;
            case self::RESPONSE_CODE_HELD:
            case self::RESPONSE_CODE_DECLINED:
            case self::RESPONSE_CODE_ERROR:
                $exceptionMessage = $this->_wrapGatewayError($result->getResponseReasonText());
                break;
            default:
                $exceptionMessage = Mage::helper('paygate')->__('Payment pre-authorizing error.');
                break;
        }

        $exceptionMessage = Mage::helper('authorizenetcim')->getTransactionMessage(
            $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_AUTH_ONLY, $realAuthTransactionId, $card, $amount, $exceptionMessage
        );
        Mage::throwException($exceptionMessage);
    }

    // function for additional cupture after additional authorization in partial capture
    /**
     * Send cupture request to gateway for capture authorized transactions of card after additioanal authorization
     *
     * @param Mage_Payment_Model_Info $payment
     * @param decimal $amount
     * @param Varien_Object $card
     * @return Mage_Sales_Model_Order_Payment_Transaction
     */
    protected function _preauthorizeCaptureWithAuthorizeBeforeCardTransaction($payment, $amount, $card, $authTransactionId) {
        $authTransaction = $payment->getTransaction($authTransactionId);
        $realAuthTransactionId = $authTransaction->getAdditionalInformation($this->_realTransactionIdKey);

        $payment->setAnetTransType(Gorilla_AuthorizenetCim_Model_Profile::TRANS_PRIOR_AUTH_CAP);
        $payment->setTransId($realAuthTransactionId);
        $payment->setAmount($amount);

        // Get the result
        $result = $this->_postRequest($payment);

        switch ($result->getResponseCode()) {
            case self::RESPONSE_CODE_APPROVED:
                if ($result->getResponseReasonCode() == self::RESPONSE_REASON_CODE_APPROVED) {
                    $captureTransactionId = $result->getTransactionId() . '-capture';
                    $card->setLastCaptureTransId($captureTransactionId);
                    return $this->_addTransaction(
                        $payment,
                        $captureTransactionId,
                        Mage_Sales_Model_Order_Payment_Transaction::TYPE_CAPTURE,
                        array(
                            'is_transaction_closed' => 0,
                            'parent_transaction_id' => $authTransactionId
                        ),
                        array($this->_realTransactionIdKey => $result->getTransactionId()),
                        Mage::helper('authorizenetcim')->getTransactionMessage(
                            $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_PRIOR_AUTH_CAP, $result->getTransactionId(), $card, $amount
                        )
                    );
                }
                $exceptionMessage = $this->_wrapGatewayError($result->getResponseReasonText());
                break;
            case self::RESPONSE_CODE_HELD:
            case self::RESPONSE_CODE_DECLINED:
            case self::RESPONSE_CODE_ERROR:
                $exceptionMessage = $this->_wrapGatewayError($result->getResponseReasonText());
                break;
            default:
                $exceptionMessage = Mage::helper('paygate')->__('Payment capturing error.');
                break;
        }

        $exceptionMessage = Mage::helper('authorizenetcim')->getTransactionMessage(
            $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_PRIOR_AUTH_CAP, $realAuthTransactionId, $card, $amount, $exceptionMessage
        );
        Mage::throwException($exceptionMessage);
    }

    /**
     * Send void request to gateway for capture authorized transactions of card if additioanal authorization was failed
     *
     * @param Mage_Payment_Model_Info $payment
     * @param decimal $amount
     * @param Varien_Object $card
     * @return Mage_Sales_Model_Order_Payment_Transaction
     */
    protected function _preauthorizeVoidWithAuthorizeBeforeCardTransaction($payment, $card, $authTransactionId) {
        $authTransaction = $payment->getTransaction($authTransactionId);
        $realAuthTransactionId = $authTransactionId ;//$authTransaction->getAdditionalInformation($this->_realTransactionIdKey);

        $payment->setAnetTransType(Gorilla_AuthorizenetCim_Model_Profile::TRANS_VOID);
        $payment->setTransId($realAuthTransactionId);
        $result = $this->_postRequest($payment);
        switch ($result->getResponseCode()) {
            case self::RESPONSE_CODE_APPROVED:
                if ($result->getResponseReasonCode() == self::RESPONSE_REASON_CODE_APPROVED
                    || $result->getResponseReasonCode() == self::RESPONSE_REASON_CODE_ALREADY_VOIDED)
                {
                    $voidTransactionId = $result->getTransactionId() . '-void';
                    //$card->setLastTransId($voidTransactionId);
                    return $this->_addTransaction(
                        $payment,
                        $voidTransactionId,
                        Mage_Sales_Model_Order_Payment_Transaction::TYPE_VOID,
                        array(
                            'is_transaction_closed' => 1,
                            'should_close_parent_transaction' => 1,
                            'parent_transaction_id' => $authTransactionId
                        ),
                        array($this->_realTransactionIdKey => $result->getTransactionId()),
                        Mage::helper('authorizenetcim')->getTransactionMessage(
                            $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_VOID, $result->getTransactionId(), $card
                        )
                    );
                }
                $exceptionMessage = $this->_wrapGatewayError($result->getResponseReasonText());
                break;
            case self::RESPONSE_CODE_DECLINED:
            case self::RESPONSE_CODE_ERROR:
                $exceptionMessage = $this->_wrapGatewayError($result->getResponseReasonText());
                break;
            default:
                $exceptionMessage = Mage::helper('authorizenetcim')->__('Payment voiding error.');
                break;
        }

        $exceptionMessage = Mage::helper('authorizenetcim')->getTransactionMessage(
            $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_VOID, $realAuthTransactionId, $card, false, $exceptionMessage
        );
        Mage::throwException($exceptionMessage);
    }

    /**
     * Send capture request to gateway for capture authorized transactions of card
     *
     * @param Mage_Payment_Model_Info $payment
     * @param decimal $amount
     * @param Varien_Object $card
     * @return Mage_Sales_Model_Order_Payment_Transaction
     */
    protected function _preauthorizeCaptureCardTransaction($payment, $amount, $card)
    {
        $authTransactionId = $card->getLastTransId();
        $authTransaction = $payment->getTransaction($authTransactionId);
        $realAuthTransactionId = $authTransaction->getAdditionalInformation($this->_realTransactionIdKey);     
        
        $payment->setAnetTransType(Gorilla_AuthorizenetCim_Model_Profile::TRANS_PRIOR_AUTH_CAP);
        $payment->setTransId($realAuthTransactionId);
        $payment->setAmount($amount);
        
        // Get the result
        $result = $this->_postRequest($payment);

        switch ($result->getResponseCode()) {
            case self::RESPONSE_CODE_APPROVED:
                if ($result->getResponseReasonCode() == self::RESPONSE_REASON_CODE_APPROVED) {
                    $authorizenetcimTransactionType = Gorilla_AuthorizenetCim_Model_Profile::TRANS_PRIOR_AUTH_CAP;
                    $captureTransactionId = $result->getTransactionId() . '-capture';
                    $card->setLastCaptureTransId($captureTransactionId);                    
                    $transaction = $this->_addTransaction(
                            $payment,
                            $captureTransactionId,
                            Mage_Sales_Model_Order_Payment_Transaction::TYPE_CAPTURE,
                            array(
                                'is_transaction_closed' => 0,
                                'parent_transaction_id' => $authTransactionId
                            ),
                            array($this->_realTransactionIdKey => $result->getTransactionId()),
                            Mage::helper('authorizenetcim')->getTransactionMessage(
                                $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_PRIOR_AUTH_CAP, $result->getTransactionId(), $card, $amount
                            )
                        );
                    $transaction->setAuthorizenetcimTxnType($authorizenetcimTransactionType);
                    return $transaction;

                }
                $exceptionMessage = $this->_wrapGatewayError($result->getResponseReasonText());
                break;
            case self::RESPONSE_CODE_HELD:
            case self::RESPONSE_CODE_DECLINED:
            case self::RESPONSE_CODE_ERROR:
                $exceptionMessage = $this->_wrapGatewayError($result->getResponseReasonText());
                break;
            default:
                $exceptionMessage = Mage::helper('paygate')->__('Payment capturing error.');
                break;
        }

        $exceptionMessage = Mage::helper('authorizenetcim')->getTransactionMessage(
            $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_PRIOR_AUTH_CAP, $realAuthTransactionId, $card, $amount, $exceptionMessage
        );
        Mage::throwException($exceptionMessage);
    }
    
    /**
     * Void the card transaction through gateway and re-auth for remaining 
     *
     * @param Mage_Payment_Model_Info $payment
     * @param Varien_Object $card
     * @return Mage_Sales_Model_Order_Payment_Transaction
     */
    protected function _voidCardTransaction($payment, $card)
    {        
        $authTransactionId = $card->getLastTransId();        
        $authTransaction = $payment->getTransaction($authTransactionId);
        
        $realAuthTransactionId = $authTransaction->getAdditionalInformation($this->_realTransactionIdKey);

        $payment->setAnetTransType(Gorilla_AuthorizenetCim_Model_Profile::TRANS_VOID);
        $payment->setTransId($realAuthTransactionId);
        $result = $this->_postRequest($payment);        
        switch ($result->getResponseCode()) {
            case self::RESPONSE_CODE_APPROVED:
                if ($result->getResponseReasonCode() == self::RESPONSE_REASON_CODE_APPROVED 
                        || $result->getResponseReasonCode() == self::RESPONSE_REASON_CODE_ALREADY_VOIDED) 
                    {
                    $voidTransactionId = $result->getTransactionId() . '-void';
                    $card->setLastTransId($voidTransactionId);
                    return $this->_addTransaction(
                        $payment,
                        $voidTransactionId,
                        Mage_Sales_Model_Order_Payment_Transaction::TYPE_VOID,
                        array(
                            'is_transaction_closed' => 1,
                            'should_close_parent_transaction' => 1,
                            'parent_transaction_id' => $authTransactionId
                        ),
                        array($this->_realTransactionIdKey => $result->getTransactionId()),
                        Mage::helper('authorizenetcim')->getTransactionMessage(
                            $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_VOID, $result->getTransactionId(), $card
                        )
                    );
                }
                $exceptionMessage = $this->_wrapGatewayError($result->getResponseReasonText());
                break;
            case self::RESPONSE_CODE_DECLINED:
            case self::RESPONSE_CODE_ERROR:
                $exceptionMessage = $this->_wrapGatewayError($result->getResponseReasonText());
                break;
            default:
                $exceptionMessage = Mage::helper('authorizenetcim')->__('Payment voiding error.');
                break;
        }

        $exceptionMessage = Mage::helper('authorizenetcim')->getTransactionMessage(
            $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_VOID, $realAuthTransactionId, $card, false, $exceptionMessage
        );
        Mage::throwException($exceptionMessage);
    }
    
    /**
     * Refund the card transaction through gateway
     *
     * @param Mage_Payment_Model_Info $payment
     * @param Varien_Object $card
     * @return Mage_Sales_Model_Order_Payment_Transaction
     */
    protected function _refundCardTransaction($payment, $amount, $card)
    {
        /**
         * Card has last transaction with type "refund" when all captured amount is refunded.
         * Until this moment card has last transaction with type "capture".
         */
        $credit_memo = Mage::registry('current_creditmemo');        
        $captureTransactionId = $credit_memo->getInvoice()->getTransactionId();
        $captureTransaction = $payment->getTransaction($captureTransactionId);
        $realCaptureTransactionId = $captureTransaction->getAdditionalInformation($this->_realTransactionIdKey);

        $payment->setAnetTransType(Gorilla_AuthorizenetCim_Model_Profile::TRANS_REFUND);
        $payment->setTransId($realCaptureTransactionId);
        $payment->setAmount($amount);
        $result = $this->_postRequest($payment);

        switch ($result->getResponseCode()) {
            case self::RESPONSE_CODE_APPROVED:
                if ($result->getResponseReasonCode() == self::RESPONSE_REASON_CODE_APPROVED) {
                    $refundTransactionId = $result->getTransactionId() . '-refund';
                    $shouldCloseCaptureTransaction = 0;
                    /**
                     * If it is last amount for refund, transaction with type "capture" will be closed
                     * and card will has last transaction with type "refund"
                     */
                    if ($this->_formatAmount($card->getCapturedAmount() - $card->getRefundedAmount()) == $amount) {
                        $card->setLastTransId($refundTransactionId);
                        $shouldCloseCaptureTransaction = 1;
                    }
                    return $this->_addTransaction(
                        $payment,
                        $refundTransactionId,
                        Mage_Sales_Model_Order_Payment_Transaction::TYPE_REFUND,
                        array(
                            'is_transaction_closed' => 1,
                            'should_close_parent_transaction' => $shouldCloseCaptureTransaction,
                            'parent_transaction_id' => $captureTransactionId
                        ),
                        array($this->_realTransactionIdKey => $result->getTransactionId()),
                        Mage::helper('authorizenetcim')->getTransactionMessage(
                            $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_REFUND, $result->getTransactionId(), $card, $amount
                        )
                    );
                }
                $exceptionMessage = $this->_wrapGatewayError($result->getResponseReasonText());
                break;
            case self::RESPONSE_CODE_DECLINED:
            case self::RESPONSE_CODE_ERROR:  
                $exceptionMessage = $this->_wrapGatewayError($result->getResponseReasonText());
                break;
            default:
                $exceptionMessage = Mage::helper('authorizenetcim')->__('Payment refunding error.');
                break;
        }

        $exceptionMessage = Mage::helper('authorizenetcim')->getTransactionMessage(
            $payment, Gorilla_AuthorizenetCim_Model_Profile::TRANS_REFUND, $realCaptureTransactionId, $card, $amount, $exceptionMessage
        );

        Mage::throwException($exceptionMessage);
    }
    
    /**
     * Post the request to the Soap Client in the Profile Model
     * 
     * @param type $payment
     * @return type 
     */
    public function _postRequest($payment)
    {                                           
        // Get the direct response
        if ($payment->getAuthorizenetcimCustomerId() && $payment->getAuthorizenetcimPaymentId()) {
            $responseBody = Mage::getModel('authorizenetcim/profile')->createCustomerTransaction($payment);
        } else {
            $responseBody = false;
        }                      
        
        $result = Mage::getModel('authorizenetcim/gateway_result');
        
        // Parse the direct response
        if ($responseBody) {
            $r = explode(self::RESPONSE_DELIM_CHAR, $responseBody);
        } else {
            $r = false;
        }
        
        if ($r) {
            $result->setResponseCode((int)str_replace('"','',$r[0]))
                ->setResponseSubcode((int)str_replace('"','',$r[1]))
                ->setResponseReasonCode((int)str_replace('"','',$r[2]))
                ->setResponseReasonText($r[3])
                ->setApprovalCode($r[4])
                ->setAvsResultCode($r[5])
                ->setTransactionId($r[6])
                ->setInvoiceNumber($r[7])
                ->setDescription($r[8])
                ->setAmount($r[9])
                ->setMethod($r[10])
                ->setTransactionType($r[11])
                ->setCustomerId($r[12])
                ->setMd5Hash($r[37])
                ->setCardCodeResponseCode($r[38])
                ->setCAVVResponseCode( (isset($r[39])) ? $r[39] : null)
                ->setSplitTenderId($r[52])
                ->setAccNumber($r[50])
                ->setCardType($r[51])
                ->setRequestedAmount($r[53])
                ->setBalanceOnCard($r[54])
                ->setCcLast4(substr($r[50], -4))
                ;
        } else {
             Mage::throwException(
                Mage::helper('authorizenetcim')->__('Error in payment gateway.')
            );
        }
        
        return $result;
    }
    
    /**
     * Gateway response wrapper
     *
     * @param string $text
     * @return string
     */
    protected function _wrapGatewayError($text)
    {
        return Mage::helper('authorizenetcim')->__('Gateway error: %s', $text);
    }

    /**
     * Retrieve session object
     *
     * @return Mage_Core_Model_Session_Abstract
     */
    protected function _getSession()
    {
        if (Mage::app()->getStore()->isAdmin()) {
            return Mage::getSingleton('adminhtml/session_quote');
        } else {
            return Mage::getSingleton('checkout/session');
        }
    }
    
    /**
     * It sets card`s data into additional information of payment model
     *
     * @param Gorilla_AuthorizenetCim_Model_Gateway_Result $response
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return Varien_Object
     */
    protected function _registerCard(Varien_Object $response, Mage_Sales_Model_Order_Payment $payment)
    {
        $cardsStorage = $this->getCardsStorage($payment);
        $card = $cardsStorage->registerCard();
        $card
            ->setRequestedAmount($response->getRequestedAmount())
            ->setBalanceOnCard($response->getBalanceOnCard())
            ->setLastTransId($response->getTransactionId())
            ->setProcessedAmount($response->getAmount())
            ->setCcType($response->getCardType())
            ->setCcOwner($payment->getCcOwner())
            ->setCcLast4($response->getCcLast4())
            ->setCcExpMonth($payment->getCcExpMonth())
            ->setCcExpYear($payment->getCcExpYear())
            ->setCcSsIssue($payment->getCcSsIssue())
            ->setCcSsStartMonth($payment->getCcSsStartMonth())
            ->setCcSsStartYear($payment->getCcSsStartYear());

        $cardsStorage->updateCard($card);
        //$this->_clearAssignedData($payment);
        return $card;
    }
    
    /**
     * Add payment transaction
     *
     * @param Mage_Sales_Model_Order_Payment $payment
     * @param string $transactionId
     * @param string $transactionType
     * @param array $transactionDetails
     * @param array $transactionAdditionalInfo
     * @return null|Mage_Sales_Model_Order_Payment_Transaction
     */
    protected function _addTransaction(Mage_Sales_Model_Order_Payment $payment, $transactionId, $transactionType,
        array $transactionDetails = array(), array $transactionAdditionalInfo = array(), $message = false
    ) {
        $payment->setTransactionId($transactionId);        
        $payment->setLastTransId($transactionId);
        $payment->resetTransactionAdditionalInfo(); //class Mage_Sales_Model_Order_Payment extends Mage_Payment_Model_Info
        foreach ($transactionDetails as $key => $value) {
            $payment->setData($key, $value);
        }

        foreach ($transactionAdditionalInfo as $key => $value) {
            $payment->setTransactionAdditionalInfo($key, $value);
        }

        $transaction = $payment->addTransaction($transactionType, null, false , $message);
        /**
         * It for self using
         */
        $transaction->setMessage($message);

        return $transaction;
    }
    
    /**
     * Init cards storage model
     *
     * @param Mage_Payment_Model_Info $payment
     */
    protected function _initCardsStorage($payment)
    {
        $this->_cardsStorage = Mage::getModel('authorizenetcim/gateway_cards')->setPayment($payment);
    }

    /**
     * Return cards storage model
     *
     * @param Mage_Payment_Model_Info $payment
     * @return Gorilla_AuthorizenetCim_Model_Gateway_Cards
     */
    public function getCardsStorage($payment = null)
    {
        if (is_null($payment)) {
            $payment = $this->getInfoInstance();
        }
        if (is_null($this->_cardsStorage)) {
            $this->_initCardsStorage($payment);
        }
        return $this->_cardsStorage;
    }    
        
    /**
     * Need to determine what the scenario here is. It could be any of the 
     * following:
     * 
     * 1. Customer that has a stored CIM profile and payment profile, just use
     *    what we have stored.
     * 
     * 2. Customer adding a new credit card - add the card and then use it.
     * 
     * 3. Existing Magento customer with no CIM profile and no card
     * 
     * 4. Guest checking out OR a customer using a card ONCE and NOT saving.
     *    Need to setup a profile just for this order.
     * 
     */
    protected function _getAuthorizationScenario($payment)
    {
        $info = $this->getInfoInstance();
        /**
         * 1. Is this an existing customer using an existing card?
         */
        if ($info->getAdditionalInformation('authorizenetcim_customer_id') && $info->getAdditionalInformation('authorizenetcim_payment_id')) {
            return self::SCENARIO_CUSTOMER_STORED_COMPLETE;
        }        

        /**
         *  2. Is this an existing customer that is just adding a new card?
         */
        if ($this->isCustomer() && $info->getAdditionalInformation('authorizenetcim_customer_id') && $this->isSavingCc()) {
            return self::SCENARIO_CUSTOMER_ADD_CARD;
        }

        /**
         * 3. Is this an existing customer that has NO CIM profile and NO card?
         */
        if (($this->isCustomer() || $this->isRegistrant()) && !$info->getAdditionalInformation('authorizenetcim_customer_id')) {
            return self::SCENARIO_CUSTOMER_ADD_PROFILE;
        }
        
        /**
         * 4. Is this just a one-time use of the card? If so, this could be a 
         * guest, a registrant, or 
         */
        if (!$this->isSavingCc()) {
            return self::SCENARIO_NO_SAVE;
        }
         
    }        
    
    /**
     * Is this an existing customer?
     */
    private function isCustomer()
    {
        // If we have a customer ID, then this is a customer, nothing else to check
        if ($this->getCustomer()->getId())
        {
            return true;
        }
        return false;
    }
    
    /**
     * Is this a new registrant?
     */
    private function isRegistrant()
    {
        // If this has no customer ID and has no password hash OR is from the admin panel, this is a new register
        if (!$this->getCustomer()->getId() && (Mage::getSingleton('checkout/session')->getQuote()->getPasswordHash() || Mage::getSingleton('customer/session')->getCustomer()->getPasswordHash() || $this->isAdmin())) {

            return true;
        }
        return false;
    }
    
    /**
     * Is this a guest?
     */
    private function isGuest()
    {
        // If no customer ID and no password hash, then this is a guest.
        if (!$this->getCustomer()->getId() && !Mage::getSingleton('checkout/session')->getQuote()->getPasswordHash() && !Mage::getSingleton('customer/session')->getCustomer()->getPasswordHash()) {
            return true;
        }
        return false;
    }
    
    /**
     * Check to see if we're inside the admin panel
     * 
     * @return bool
     */
    public function isAdmin()
    {
        if (Mage::app()->getStore()->isAdmin()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function isSavingCc()
    {
        if ($this->isGuest() || ($this->getConfigData('save_optional') && !$this->getInfoInstance()->getAdditionalInformation('cc_save_card'))) {
            return false;
        } else {
            return true;
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
        return $this->magentoCustomer;
    }
    
    /**
     * Assign data to info model instance
     *
     * @param   mixed $data
     * @return  Gorilla_AuthorizenetCim_Model_Gateway
     */
    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }               
        
        if ($data->getCcSaveCard() == "Yes") {
            $cc_save_card = true;
        } else {
            $cc_save_card = false;
        }
        
        $info = $this->getInfoInstance();
        $info->setCcType($data->getCcType())
            ->setCcOwner($data->getCcOwner())
            ->setCcLast4(substr($data->getCcNumber(), -4))
            ->setCcNumber($data->getCcNumber())
            ->setCcCid($data->getCcCid())
            ->setCcExpMonth($data->getCcExpMonth())
            ->setCcExpYear($data->getCcExpYear())
            ->setCcSsIssue($data->getCcSsIssue())
            ->setCcSsStartMonth($data->getCcSsStartMonth())
            ->setCcSsStartYear($data->getCcSsStartYear())
            ->setAdditionalInformation('authorizenetcim_customer_id',$data->getAuthorizenetcimCustomerId())
            ->setAdditionalInformation('authorizenetcim_payment_id',$data->getAuthorizenetcimPaymentId())
            ->setAdditionalInformation('cc_save_card',$cc_save_card)
            ;
        return $this;
    }
    
    /**
     * Construct a new profile request for the customer
     */
    protected function _buildProfileRequest($payment, $forcedCustomerId = false)
    {
        $customer_id = ($forcedCustomerId) ? $customer_id : $this->getCustomer()->getId();
        if (!$customer_id || !$this->isSavingCc())  {
            $customer_id = $payment->getOrder()->getIncrementId() . now(); //Make up a customer ID so we don't ever have duplicate issues
            $description = "Guest or Unsaved Card";
        } else {
            $description = "Magento Customer ID: $customer_id"; 
        }
                        
        $cimGatewayId = $this->getCustomer()->getCimGatewayId();

        $billingAddress = $payment->getOrder()->getBillingAddress();
        $customer = new Varien_Object;
        $customer->setEmail($payment->getOrder()->getCustomerEmail())
                ->setId($customer_id)
                ->setDescription($description)
                ->setGatewayId($cimGatewayId)
                ->setFirstname($billingAddress->getFirstname())
                ->setLastname($billingAddress->getLastname())
                ->setCompany($billingAddress->getCompany())
                ->setAddress($billingAddress->getStreet(true))
                ->setCity($billingAddress->getCity())
                ->setState($billingAddress->getRegion())
                ->setZip($billingAddress->getPostcode())
                ->setCountry($billingAddress->getCountryId());

        $payment_profile = new Varien_Object;
        $customer->setPaymentProfile($payment_profile);        

        $customer->getPaymentProfile()
                ->setCc($payment->getCcNumber())
                ->setCcv($payment->getCcCid())
                ->setExpiration(sprintf('%04d-%02d', $payment->getCcExpYear(), $payment->getCcExpMonth() ));

        return $customer;
    }
    
    /**
     * Check void availability
     *
     * @param   Varien_Object $invoicePayment
     * @return  bool
     */
    public function canVoid(Varien_Object $payment)
    {
        if ($this->_isGatewayActionsLocked($this->getInfoInstance())) {
            return false;
        }
        return $this->_isPreauthorizeCapture($this->getInfoInstance());
    }
    
    /**
     * Return true if there are authorized transactions
     *
     * @param Mage_Payment_Model_Info $payment
     * @return bool
     */
    protected function _isPreauthorizeCapture($payment)
    {
        if ($this->getCardsStorage()->getCardsCount() <= 0) {            
            return false;
        }
        foreach($this->getCardsStorage()->getCards() as $card) {
            $lastTransaction = $payment->getTransaction($card->getLastTransId());
            if (!$lastTransaction || $lastTransaction->getTxnType() != Mage_Sales_Model_Order_Payment_Transaction::TYPE_AUTH) {                
                return false;
            }


        }
        return true;
    }
    
    /**
     * If gateway actions are locked return true
     *
     * @param  Mage_Payment_Model_Info $payment
     * @return bool
     */
    protected function _isGatewayActionsLocked($payment)
    {
        return $payment->getAdditionalInformation($this->_isGatewayActionsLockedKey);
    }

    /**
     * Process exceptions for gateway action with a lot of transactions
     *
     * @param  Mage_Payment_Model_Info $payment
     * @param  string $messages
     * @param  bool $isSuccessfulTransactions
     */
    protected function _processFailureMultitransactionAction($payment, $messages, $isSuccessfulTransactions)
    {
        if ($isSuccessfulTransactions) {
            $messages[] = Mage::helper('authorizenetcim')->__('Gateway actions are locked because the gateway cannot complete one or more of the transactions. Please log in to your Authorize.Net account to manually resolve the issue(s).');
            /**
             * If there is successful transactions we can not to cancel order but
             * have to save information about processed transactions in order`s comments and disable
             * opportunity to voiding\capturing\refunding in future. Current order and payment will not be saved because we have to
             * load new order object and set information into this object.
             */
            $currentOrderId = $payment->getOrder()->getId();
            $copyOrder = Mage::getModel('sales/order')->load($currentOrderId);
            $copyOrder->getPayment()->setAdditionalInformation($this->_isGatewayActionsLockedKey, 1);
            foreach($messages as $message) {
                $copyOrder->addStatusHistoryComment($message);
            }
            $copyOrder->save();
        }
        Mage::throwException(Mage::helper('authorizenetcim')->convertMessagesToMessage($messages));
    }
    
    /**
     * Mark capture transaction id in invoice
     *
     * @param Mage_Sales_Model_Order_Invoice $invoice
     * @param Mage_Sales_Model_Order_Payment $payment
     * @return Mage_Payment_Model_Method_Abstract
     */
    public function processInvoice($invoice, $payment)
    {
        $lastCaptureTransId = '';
        $cardsStorage = $this->getCardsStorage($payment);      
        foreach($cardsStorage->getCards() as $card) {
            $lastCapId = $card->getData('last_capture_trans_id');
            if ($lastCapId && !empty($lastCapId) && !is_null($lastCapId)) {
                $lastCaptureTransId = $lastCapId;
                break;
            }
        }

        $invoice->setTransactionId($lastCaptureTransId);
        return $this;
    }
    
    /**
     * Check capture availability
     *
     * @return bool
     */
    public function canCapture()
    {
        if ($this->_isGatewayActionsLocked($this->getInfoInstance())) {
            return false;
        }
        if ($this->_isPreauthorizeCapture($this->getInfoInstance())) {
            return true;
        }

        /**
         * If there are not transactions it is placing order and capturing is available
         */
        foreach($this->getCardsStorage()->getCards() as $card) {
            $lastTransaction = $this->getInfoInstance()->getTransaction($card->getLastTransId());
            if ($lastTransaction) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Round up and cast specified amount to float or string
     *
     * @param string|float $amount
     * @param bool $asFloat
     * @return string|float
     */
    protected function _formatAmount($amount, $asFloat = false)
    {
        $amount = sprintf('%.2F', $amount); // "f" depends on locale, "F" doesn't
        return $asFloat ? (float)$amount : $amount;
    }
    
    /**
     * Format the full card name into the 2 character Magento short code for the
     * card type.
     * 
     * @param string $ccType
     * @return string $ccType
     */
    protected function _formatCcType($ccType)
    {
        $allTypes = Mage::getSingleton('payment/config')->getCcTypes();
        $allTypes = array_flip($allTypes);
        
        if (isset($allTypes[$ccType]) && !empty($allTypes[$ccType])) {
            return $allTypes[$ccType];
        }
        
        return $ccType;
    }
}