<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * Class for interacting with CIM profile management via SOAP
 *
 * For documentation:
 * @see http://www.authorize.net/support/CIM_SOAP_guide.pdf
 * @see https://api.authorize.net/soap/v1/Service.asmx?WSDL
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Model_Profile extends Mage_Core_Model_Abstract
{       
    // Customer Account Transactions
    const TRANS_CREATE_CUSTOMER = 'CreateCustomerProfile';
    const TRANS_GET_CUSTOMER    = 'GetCustomerProfile';
    const TRANS_UPDATE_CUSTOMER = 'UpdateCustomerProfile';
    const TRANS_DELETE_CUSTOMER = 'DeleteCustomerProfile';
    
    // Customer Payment Profile Transactions
    const TRANS_CREATE_PROFILE  = 'CreateCustomerPaymentProfile';
    const TRANS_GET_PROFILE     = 'GetCustomerPaymentProfile';    
    const TRANS_UPDATE_PROFILE  = 'UpdateCustomerPaymentProfile';
    const TRANS_DELETE_PROFLE   = 'DeleteCustomerPaymentProfile';
    
    // Payment Transaction Types
    const TRANS_CREATE_TRANS    = 'CreateCustomerProfileTransaction';
    const TRANS_AUTH_ONLY       = 'profileTransAuthOnly';    
    const TRANS_AUTH_CAPTURE    = 'profileTransAuthCapture';
    const TRANS_PRIOR_AUTH_CAP  = 'profileTransPriorAuthCapture';
    const TRANS_CAPTURE_ONLY    = 'profileTransCaptureOnly';
    const TRANS_VOID            = 'profileTransVoid';
    const TRANS_REFUND          = 'profileTransRefund';
    
    // Debug Settings
    const LOG_FILE              = 'authorizenetcim';
    const MESSAGES_NODE         = 'messages';
    
    // Success Codes
    protected $_successCodes = array(
        'I00001',
        'I00003'
    );
    
    // Error Codes - Any of these prevent the profile from saving
    protected $_errorCodes = array(
        'E00001', 'E00002', 'E00003', 'E00004', 'E00005', 'E00006', 'E00007', 
        'E00008', 'E00009', 'E00010', 'E00011', 'E00013', 'E00014', 'E00015',
        'E00016', 'E00019', 'E00027', 'E00029', 'E00039', 'E00040', 'E00041',
        'E00042', 'E00043', 'E00044', 'E00051', 
    );
    
    
    // Error Messages - Messages associated with above codes
    protected $_errorMessages = array(        
        'E00001' => 'A system error has occurred. Please try again.',
        'E00002' => 'Unsupported Content Type',
        'E00003' => 'Invalid XML',
        'E00004' => 'Invalid XML',
        'E00005' => 'Invalid Transaction Key',
        'E00006' => 'Invalid API Key',
        'E00007' => 'Invalid gateway credentials',
        'E00008' => 'Invalid gateway credentials',
        'E00009' => 'Method cannot be executed in Test Mode',
        'E00010' => 'The user does not have permission to call the API',
        'E00011' => 'The user does not have permission to call the API method',
        'E00013' => 'One or more field values are not valid',
        'E00014' => 'Required value missing',
        'E00015' => 'Invalid length on required value',
        'E00016' => 'Field Type Not Valid',
        'E00019' => 'Tax ID or Driver\'s License Required',
        'E00027' => ' An approval was not returned for the transaction',
        'E00029' => 'Payment information is required when creating a payment profile',
        'E00039' => 'A duplicate record already exists',
        'E00040' => 'Customer record could not be found',
        'E00041' => 'All fields were empty',
        'E00042' => 'You have reached the maximum number of payment methods that may be created',
        'E00043' => 'You have reached the maximum number of shipping addresses that may be created',
        'E00044' => 'The gateway is not enabled for CIM',
        'E00051' => 'Payment profiles do not match'
    );
    
    /**
     * Constructor
     */
    public function _construct() 
    {
        $this->_init('authorizenetcim/profile');
    }
    
    /**
     * Get the URL to the Auth.net WSDL
     * 
     * @return string $wsdl_url
     */
    public function getWsdlUrl()
    {
        if ($this->isTestMode())
        {
            return $this->_getConfig('test_gateway_wsdl');
        } else {
            return $this->_getConfig('gateway_wsdl');
        }
    }

    /**
     * Get the URL to the Auth.net Gateway API
     * 
     * @return string $gateway_url
     */
    public function getGatewayUrl()
    {
        if ($this->isTestMode())
        {
            return $this->_getConfig('test_gateway_url');
        } else {
            return $this->_getConfig('gateway_url');
        }
    }

    /**
     * Get the transaction key
     * 
     * @return string $transaction_key
     */
    public function getTransactionKey()
    {
        if ($this->isTestMode())
        {
            return $this->_getConfig('test_trans_key');
        } else {
            return $this->_getConfig('trans_key');
        }
    }

    /**
     * Get the API login
     * 
     * @return string $api_login
     */
    public function getApiLogin()
    {
        if ($this->isTestMode())
        {
            return $this->_getConfig('test_login');
        } else {
            return $this->_getConfig('login');
        }
    }

    /**
     * Are we in test mode?
     * 
     * @return bool
     */
    public function isTestMode()
    {
        return $this->_getConfig('test');	
    }

    public function isAllowGuestCimProfile()
    {
        return $this->_getConfig('allow_guest_cim_profile');
    }

    public function isAuthForPartialCapture()
    {
        return $this->_getConfig('auth_for_partial_capture');
    }

    /**
     * Get config settings for the gateway
     * 
     * @param string $key
     * @return mixed
     */
    protected function _getConfig($key)
    {
        return Mage::getSingleton('authorizenetcim/gateway')->getConfigData($key);
    }
    
    /**
     * Log debug settings
     * 
     * @param mixed $debugData 
     */
    function debugData($debugData)
    {
        Mage::getModel('authorizenetcim/gateway')->debugData($debugData);
    }
            
    /**
     * Wrapper for retrieving SoapClient. Will return it if it already exists
     * or create it if it does not.
     * 
     * @return SoapClient
     */
    public function getSoapClient()
    {
        if (!$this->getData('soap_client') instanceof SoapClient)
        {                        
            try {            
                $soap_client= new SoapClient($this->getWsdlUrl(),
                    array(
                        'connection_timeout' => 2, // If Authorize.net isn't responding within two seconds, it's down
                        'exceptions' => true, // Throw exceptions if encountered - these should be caught by code
                        'trace' => ($this->_getConfig('debug')) ? 1 : 0, // If debug is enabled, use the trace
                        'compression'=> SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP // request a compressed response
                    )
                );          
                $soap_client->__setLocation($this->getGatewayUrl());
                $this->setData('soap_client',$soap_client);
            } catch (SoapFault $sf) {
                //Log SOAP fault from connection
                if ($this->_getConfig('debug'))
                {
                    $this->debugData($sf);
                }                
                return false;
            } catch (Exception $e) {            
                //Log Exception from Connection
                if ($this->_getConfig('debug'))
                {
                    $this->debugData($e);
                }     
                return false;
            } 
        }
        
        return $this->getData('soap_client'); // Return the client to the user
    }
    
    protected function _getAuthentication()
    {
        return array(
                'name' => $this->getApiLogin(),
                'transactionKey' => $this->getTransactionKey());
    }
       
    /**
     * Create the base profile for a customer
     * 
     * @param Mage_Customer_Model_Customer $customer 
     * @param boolean $paymentProfile - Flag to create payment profile
     * @return SoapClient::Response
     */
    public function createCustomerProfile($customer, $paymentProfile = false)
    {        
        /**
         * Create the base customer information
         */
        $soap_env = array(
            self::TRANS_CREATE_CUSTOMER =>  array(
                'merchantAuthentication'  => $this->_getAuthentication(),
                'profile' => array(
                    'merchantCustomerId' => $customer->getId(),
                    'description' => Mage::getBaseUrl() . ": " . $customer->getDescription() . " : " . now(), //Base URL + Description + timestamp to avoid duplicates
                    'email' => $customer->getEmail()
                )                
            )
        );
        
        /**
         * If we have a payment profile, let's create both at the same time.
         */
        if ($paymentProfile)
        {
            $soap_env[self::TRANS_CREATE_CUSTOMER]['profile']['paymentProfiles'] = array(
                array(
                    'customerType' => 'individual',
                    'billTo' => array(
                        'firstName' => $customer->getFirstname(),
                        'lastName' => $customer->getLastname(),
                        'company' => $customer->getCompany(),
                        'address' => $customer->getAddress(),
                        'city' => $customer->getCity(),
                        'state' => $customer->getState(),
                        'zip' => $customer->getZip(),
                        'country' => $customer->getCountry(),
                        'phoneNumber' => $customer->getPhoneNumber(),
                    ),
                    'payment' => array(
                        'creditCard' => array(
                            'cardNumber' => $customer->getPaymentProfile()->getCc(),
                            'expirationDate' => $customer->getPaymentProfile()->getExpiration(),
                            'cardCode' => $customer->getPaymentProfile()->getCcv()
                        )
                    )  
                )
            );
            $soap_env[self::TRANS_CREATE_CUSTOMER]['validationMode'] = $this->_getConfig('validation_mode'); // Use this if we're trying to add cards
        } else {
            $soap_env[self::TRANS_CREATE_CUSTOMER]['validationMode'] = Gorilla_AuthorizenetCim_Model_Gateway::VALIDATION_MODE_NONE; // Use this if we're not trying to add cards
        }
        
        $response = $this->doCall(self::TRANS_CREATE_CUSTOMER, $soap_env);        
        
        if (!$response)
            return false;
        
        $hasErrors = $this->_checkErrors($response);
        if (!$hasErrors)
        {
            $customerProfile = new Varien_Object();
            $customerProfile->setCustomerProfileId($response->CreateCustomerProfileResult->customerProfileId);
            
            if ($paymentProfile)
                $customerProfile->setCustomerPaymentProfileId($response->CreateCustomerProfileResult->customerPaymentProfileIdList->long);
            
            return $customerProfile;
        } else {            
            return false;
        }
    }
    
    /**
     * Retrieve the base profile for a customer
     * 
     * @param Mage_Customer_Model_Customer $customer 
     * @return SoapClient::Response
     */
    public function getCustomerProfile($cim_profile_id)
    {
        $soap_env = array(
            self::TRANS_GET_CUSTOMER => array(
                'merchantAuthentication'  => $this->_getAuthentication(),
                'customerProfileId' => $cim_profile_id
            )
        );
        
        $response = $this->doCall(self::TRANS_GET_CUSTOMER, $soap_env);         
        
        if (!$response)
            return false;
        
        $hasErrors = $this->_checkErrors($response);
        if (!$hasErrors)
        {
            return $response->GetCustomerProfileResult->profile;
        } else {            
            return false;
        }
    }
    
    /**
     * Create a payment profile for the given customer
     *  
     * @param Mage_Core_Model_Customer $customer
     * @return SoapClient::Response
     */
    /**
     * Create a payment profile for the given customer
     *  
     * @param Mage_Core_Model_Customer $customer
     * @return SoapClient::Response
     */
    public function createCustomerPaymentProfile($customer)
    {
        $soap_env = array(
            self::TRANS_CREATE_PROFILE =>  array(
                'merchantAuthentication'  => $this->_getAuthentication(),
                'customerProfileId' => $customer->getGatewayId(),
                'paymentProfile' => array(
                    'customerType' => 'individual',
                    'billTo' => array(
                        'firstName' => $customer->getFirstname(),
                        'lastName' => $customer->getLastname(),
                        'company' => $customer->getCompany(),
                        'address' => $customer->getAddress(),
                        'city' => $customer->getCity(),
                        'state' => $customer->getState(),
                        'zip' => $customer->getZip(),
                        'country' => $customer->getCountry(),
                        'phoneNumber' => $customer->getPhoneNumber(),
                    ),
                    'payment' => array(
                        'creditCard' => array(
                            'cardNumber' => $customer->getPaymentProfile()->getCc(),
                            'expirationDate' => $customer->getPaymentProfile()->getExpiration(),
                            'cardCode' => $customer->getPaymentProfile()->getCcv()
                        )
                    )
                ),
                'validationMode' => $this->_getConfig('validation_mode')
            )
        );
        
        $response = $this->doCall(self::TRANS_CREATE_PROFILE, $soap_env);         
        
        if (!$response)
            return false;
        
        $hasErrors = $this->_checkErrors($response);
        if (!$hasErrors)
        {
            return (int) $response->CreateCustomerPaymentProfileResult->customerPaymentProfileId;
        } else {            
            return false;
        }
                
    }
    
    /**
     * Get the details of the customer payment profile
     * 
     * @param Mage_Core_Model_Customer $customer
     * @return SoapClient::Response
     */
    public function getCustomerPaymentProfile($cim_customer_id, $cim_token_id)
    {
        $soap_env = array(
            self::TRANS_GET_PROFILE => array(
                'merchantAuthentication'  => $this->_getAuthentication(),
                'customerProfileId' => $cim_customer_id,
                'customerPaymentProfileId' => $cim_token_id,
            )
        );
          
        $response = $this->doCall(self::TRANS_GET_PROFILE, $soap_env);         
        
        if (!$response)
            return false;
        
        $hasErrors = $this->_checkErrors($response);
        if (!$hasErrors)
        {
            return $response->GetCustomerPaymentProfileResult->paymentProfile;
        } else {            
            return false;
        }
    }
    
    /**
     * Update the Customer
     * 
     * @param type $customer
     * @return SoapClient::Response
     */
    public function updateCustomerProfile($customer)
    {
        $soap_env = array(
            self::TRANS_UPDATE_CUSTOMER =>  array(
                'merchantAuthentication'  => $this->_getAuthentication(),             
                'profile' => array(
                    'merchantCustomerId' => $customer->getId(),
                    'description' => $customer->getDescription(),
                    'customerProfileId' => $customer->getGatewayId(),
                    'email' => $customer->getEmail()
                )
            )
        );
        
        return $this->doCall(self::TRANS_UPDATE_CUSTOMER, $soap_env);     
    }
    
    /**
     * Update the Customer's payment profile
     * 
     * @param type $customer
     * @return SoapClient::Response
     */
    public function updateCustomerPaymentProfile($customer)
    {
        $soap_env = array(
            self::TRANS_UPDATE_PROFILE =>  array(
                'merchantAuthentication'  => $this->_getAuthentication(),
                'customerProfileId' => $customer->getGatewayId(),      
                'paymentProfile' => array(
                    'customerType' => 'individual',
                    'billTo' => array(
                        'firstName' => $customer->getFirstname(),
                        'lastName' => $customer->getLastname(),
                        'company' => $customer->getCompany(),
                        'address' => $customer->getAddress(),
                        'city' => $customer->getCity(),
                        'state' => $customer->getState(),
                        'zip' => $customer->getZip(),
                        'country' => $customer->getCountry(),
                        'phoneNumber' => $customer->getPhoneNumber(),
                    ),
                    'payment' => array(
                        'creditCard' => array(
                            'cardNumber' => $customer->getPaymentProfile()->getCc(),
                            'expirationDate' => $customer->getPaymentProfile()->getExpiration(),
                            'cardCode' => $customer->getPaymentProfile()->getCcv()
                        ),
                    ),
                    'customerPaymentProfileId' => $customer->getPaymentProfile()->getGatewayId(),
                ),                                
                'validationMode' => $this->_getConfig('validation_mode')
            )
        );
        
        $response = $this->doCall(self::TRANS_UPDATE_PROFILE, $soap_env);         
        
        if (!$response)
            return false;
        
        $hasErrors = $this->_checkErrors($response);
        if (!$hasErrors)
        {
            return true;
        } else {            
            return false;
        }   
    }
    
    /**
     * Delete the customer
     * 
     * @param type $customer
     * @return SoapClient::Response
     */
    public function deleteCustomer($customer)
    {
        $soap_env = array(
            self::TRANS_DELETE_CUSTOMER =>  array(
                'merchantAuthentication'  => $this->_getAuthentication(),
                'customerProfileId' => $customer->getGatewayId()
            )
        );
        
        return $this->doCall(self::TRANS_DELETE_CUSTOMER, $soap_env); 
    }
    
    /**
     * Delete the customer's payment profile
     * 
     * @param type $customer
     * @return SoapClient::Response
     */
    public function deleteCustomerPaymentProfile($customerProfileId, $customerPaymentProfileId)
    {
        $soap_env = array(
            self::TRANS_DELETE_PROFLE =>  array(
                'merchantAuthentication'  => $this->_getAuthentication(),
                'customerProfileId' => $customerProfileId,
                'customerPaymentProfileId' => $customerPaymentProfileId
            )
        );
        
        return $this->doCall(self::TRANS_DELETE_PROFLE, $soap_env); 
    }
    
    /**
     * Perform transaction (auth, capture, etc.)
     * 
     * @param Mage_Sales_Model_Payment $payment
     * @return string $directResponse|bool
     */
    public function createCustomerTransaction($payment)
    {                        
        /**
         * Set the order in its own object
         */
        $order = $payment->getOrder();
        
        /**
         * Create the transaction
         */
        $soap_env = array(
            self::TRANS_CREATE_TRANS =>  array(
                'merchantAuthentication'  => $this->_getAuthentication(),
                'transaction' => array(
                    $payment->getAnetTransType() => array(
                        'amount' => $payment->getAmount(),
                        'tax' => array(
                            'amount' => $order->getBaseTaxAmount()
                        ),
                        'shipping' => array(
                            'amount' => $order->getBaseShippingAmount()
                        ),
                        'customerProfileId' => $payment->getAuthorizenetcimCustomerId(),
                        'customerPaymentProfileId' => $payment->getAuthorizenetcimPaymentId(),
                        'order' => array(
                            'invoiceNumber' => $order->getIncrementId()
                        ),
                        //'cardCode' => $payment->getCcCid()                    
                    )   
                ),
                'extraOptions' => 'x_delim_char='.  Gorilla_AuthorizenetCim_Model_Gateway::RESPONSE_DELIM_CHAR
            )
        ); 

        // If this is a prior auth capture, void, or refund add the transaction id
        if ($payment->getAnetTransType() == self::TRANS_PRIOR_AUTH_CAP 
                || $payment->getAnetTransType() == self::TRANS_VOID
                || $payment->getAnetTransType() == self::TRANS_REFUND) 
        {
            $soap_env[self::TRANS_CREATE_TRANS]['transaction'][$payment->getAnetTransType()]['transId'] = $payment->getTransId();
        }

        $response = $this->doCall(self::TRANS_CREATE_TRANS, $soap_env);         
        
        if (!$response)
            return false;
        
        $hasErrors = $this->_checkErrors($response);
        if ($response)
        {
            if (!$hasErrors)
            {
                return $response->CreateCustomerProfileTransactionResult->directResponse;
            } else {            
                return $response->CreateCustomerProfileTransactionResult->directResponse;
            }  
        } else {
            return false;
        }
    }
    
    /**
     * Fetch the Authorize.net CIM ID for a particular Magento customer
     * 
     * @param type $customer
     * @return type 
     */
    public function loadGatewayIdByCustomer($customer)
    {
        return $this->getResource()->loadGatewayIdByCustomer($customer);
    }
    
    /**
     * Check the response from Authorize.net for Errors
     * 
     * @param type $response 
     */
    protected function _checkErrors($response)
    {
        $errors = array();       
        
        foreach ($this->getResponseMessages() as $message)
        {                      
            if (in_array($message->code, $this->_errorCodes))
            {
                 $errors[] = $this->_errorMessages[$message->code];                                           
            }            
        }        
        
        if (!empty($errors))
        {
            $this->setErrorMessages($errors);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * We have to go through this because the response node is named differently
     * for every SOAP response but we just want the messages block. This will set
     * data in the object under the key response_messages. It will be an array
     * of any responses.
     * 
     * @param stdClass $haystack
     * @param type $needle
     */
    public function retrieveResponseMessages(stdClass $haystack, $needle = self::MESSAGES_NODE)
    {                       
        // Typecast to (array) automatically converts stdClass -> array.
        $haystack = (array) $haystack;

        // Iterate through the former properties looking for any stdClass properties.
        // Recursively apply (array).
        foreach($haystack as $key => $value)
        {
            if(is_object($value)&&get_class($value)==='stdClass')
            {
                $haystack[$key] = self::retrieveResponseMessages($value, $needle);
                if ($key == $needle)
                {
                    $messages = array();
                    if (is_array($value->MessagesTypeMessage))
                    {
                        foreach ($value->MessagesTypeMessage as $message)
                        {
                            $messages[] = $message;
                        }
                    } else {
                        $messages[] = $value->MessagesTypeMessage;
                    }

                    $this->setResponseMessages($messages);
                }
                
            }
        }
        
    }
    
    /**
     * Perform SOAP call
     * 
     * @param type $transaction
     * @param type $data
     * @return boolean 
     */
    public function doCall($transaction, $data)
    {        
        try {           
            if ($this->getSoapClient() instanceof SoapClient) {
                $response = $this->getSoapClient()->__call($transaction, $data);
            } else {
                $response = false;
            }
        } catch (SoapFault $sf) {
            $this->debugData($sf);
            $response = false;
        } catch (Exception $e) {
            $this->debugData($e);   
            $response = false;
        }
        
        if ($this->_getConfig('debug') && $this->getSoapClient() instanceof SoapClient) {
            $this->logSoapTransaction();
        }
        
        // Get response messages
        $this->retrieveResponseMessages($response);   
        
        return $response;
        
    }      
    
    public function logSoapTransaction() {
        $debug_message = '';
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($this->getSoapClient()->__getLastRequest());

        $debug_message .= "Request:\n\n";
        $debug_message .= $dom->saveXML();

        $cardNumber_start = strpos($debug_message, 'cardNumber');
        $debug_message = substr_replace($debug_message, 'XXXX', $cardNumber_start+11, 12);


        $this->debugData($debug_message);

        $debug_message = '';
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($this->getSoapClient()->__getLastResponse());

        $debug_message .= "Response:\n\n";
        $debug_message .= $dom->saveXML();

        $this->debugData($debug_message);
    }        
    
}