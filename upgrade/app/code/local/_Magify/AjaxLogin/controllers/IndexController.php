<?php
/**
 * Magify
 *
 * @category    controller
 * @package     magify_ajaxlogin
 * @copyright   Copyright (c) 2012 Magify Inc. (http://www.magify.com)
 * @version		1.0
 * @author		Magify (info@magify.com)
 */

class Magify_AjaxLogin_IndexController extends Mage_Core_Controller_Front_Action
{
	protected function _getSession()
	{
		return Mage::getSingleton('customer/session');
	}
	
	/**
	 * Login by Ajax, return result in json.
	 * @return string
	 */
	public function loginPostAction()
	{		
		$result = array();
		
		$session = $this->_getSession();
		
		if ($session->isLoggedIn()) {
			$result['logined'] = 1;
			$result['user'] = $session->getCustomer()->getName();
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
			return;
		}		

		if (!$this->getRequest()->isPost()) {
			$result['logined'] = 0;
			$result['detail'] = $this->__('Unexpected error!');
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
			return;
		}
		
		$login = $this->getRequest()->getPost('login');	
		
		if (!empty($login['username']) && !empty($login['password'])) 
		{
			try 
			{
				if ($session->login($login['username'], $login['password']))
				{
					$result['logined'] = 1;
					$result['user'] = $session->getCustomer()->getName();
				}
				
				// If this user just confirmed registration, it need to send him/her a confirmation email.
				if ($session->getCustomer()->getIsJustConfirmed()) 
				{									
					$session->getCustomer()->sendNewAccountEmail('confirmed', '', Mage::app()->getStore()->getId());
					$result['detail'] = $this->__('Thank you for registering with %s.', Mage::app()->getStore()->getFrontendName());					
				}
			} 
			catch (Mage_Core_Exception $e) 
			{
				switch ($e->getCode()) 
				{
					case Mage_Customer_Model_Customer::EXCEPTION_EMAIL_NOT_CONFIRMED:
						$value = Mage::helper('customer')->getEmailConfirmationUrl($login['username']);
						$message = Mage::helper('customer')->__('This account is not confirmed. <a href="%s">Click here</a> to resend confirmation email.', $value);
						break;
					case Mage_Customer_Model_Customer::EXCEPTION_INVALID_EMAIL_OR_PASSWORD:
						$message = $e->getMessage();
						break;
					default:
						$message = $e->getMessage();
				}
					
				$result['logined'] = 0;
				$result['detail'] = $message;
			} 
			catch (Exception $e) 
			{
				$result['logined'] = 0;
				$result['detail'] = $this->__('Unexpected error!');
			}
		} 
		else 
		{
			$result['logined'] = 0;
			$result['detail'] = $this->__('Login and password are required.');
		}
		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}
	//--------------------------------------------------------------------------------------------------
	// post zipcode by Ajax, return result in JSON
	// return string
	//--------------------------------------------------------------------------------------------------
	public function zipcodePostAction()
	{		
		$result = array();
		
		$session = $this->_getSession();
		
		
		if (!$this->getRequest()->isPost()) 
		{
			$result['inarea'] = 0;
			$result['detail'] = $this->__('Unexpected error: No Post Variables!');
			$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
			return;
		}
		
		$zipcode = $this->getRequest()->getPost('zipcode');	
		
		if (!empty($zipcode['zipcode'])) 
		{
			try 
			{
				//save user's zipcode in session
				$session->setData("customer_zipcode", $zipcode);
				
				//post to test database
				//

				//is the user's zipcode in the shipping tables?
			    $resource 	= Mage::getSingleton('core/resource');
				$query 		= 'SELECT * FROM ' . $resource->getTableName('shipping_tablerate') . ' WHERE dest_zip =' .$zipcode;
	    		$results 	= $resource->getConnection('core_read')->fetchAll($query);
				
				
				if (count($results) > 0)
				{
					$result['inarea'] = 1;
				} else {
					$result['detail'] = $this->__('Zipcode not found in shipping table.');
				}
				
			} 
			catch (Mage_Core_Exception $e) 
			{
				switch ($e->getCode()) 
				{
					default:
						$message = $e->getMessage();
				}
					
				$result['inarea'] = 0;
				$result['detail'] = $message;
			} 
			catch (Exception $e) 
			{
				$result['inarea'] = 0;
				$result['detail'] = $this->__('Unexpected error: Exception!');
			}
		} 
		else 
		{
			$result['inarea'] = 0;
			$result['detail'] = $this->__('Zipcode not found in shipping table.');
		}
		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}
	
	//--------------------------------------------------------------------------------------------------
	// post portionsize by Ajax, return result in JSON
	// return string
	//--------------------------------------------------------------------------------------------------
	public function portionsizePostAction()
	{	
		//validate portion size based upon subscription simple products
		$psize = $this->getRequest()->getPost('portionsize');	
		
		if( ($psize == '2') || ($psize == '4') || ($psize == '6') ){
			$result['psize'] = $psize;
		} else {
			$result['detail'] = $this->__('Portion size not found.');
		}
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));		
	}

	//--------------------------------------------------------------------------------------------------
	// post vegitarian by Ajax, return result in JSON
	// return string
	//--------------------------------------------------------------------------------------------------
	public function vegitarianPostAction()
	{	
		//validate vegitarian option based upon subscription simple products
		$veg = $this->getRequest()->getPost('vegitarian');	
		
		if( ($veg == 0) || ($veg == 1)){
			$result['veg'] = $veg;
		} else {
			$result['detail'] = $this->__('Vegitarian option not found.');
		}
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));		
	}

	//--------------------------------------------------------------------------------------------------
	// return string
	//--------------------------------------------------------------------------------------------------	
	public function forgotPasswordPostAction()
	{	
		$result = array();
		
		$email = (string) $this->getRequest()->getPost('email');
		if ($email) 
		{
			if (!Zend_Validate::is($email, 'EmailAddress')) 
			{
				$this->_getSession()->setForgottenEmail($email);
				
				$result['retrieved'] = 0;
				$result['detail'] = $this->__('Invalid email address.');
			}

			/** @var $customer Mage_Customer_Model_Customer */
			$customer = Mage::getModel('customer/customer')
				->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
				->loadByEmail($email);

			if ($customer->getId()) 
			{
				try 
				{
					$newResetPasswordLinkToken = Mage::helper('customer')->generateResetPasswordLinkToken();
					$customer->changeResetPasswordLinkToken($newResetPasswordLinkToken);
					$customer->sendPasswordResetConfirmationEmail();
					
					$result['retrieved'] = 1;
					$result['detail'] = Mage::helper('customer')->__('If there is an account associated with %s you will receive an email with a link to reset your password.', Mage::helper('customer')->htmlEscape($email));					
				} 
				catch (Exception $exception) 
				{
					$result['retrieved'] = 0;
					$result['detail'] = $exception->getMessage();
				}
			}
		} 
		else 
		{
			$result['retrieved'] = 0;
			$result['detail'] = $this->__('Please enter your email.');
		}
		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}
	
	public function removedpPostAction(){
		echo 'I am dp removal';
	}
	
	
	/**
	  * Create customer account action, and reponse in json.
	  * Contents in response:
	  * 1)result['registered'] = 0 stands for failing to register.
	  * 2)result['registered'] = 1 stands for succeeding to register, but waiting to confirm.
	  * 3)result['registered'] = 2 stands for succeeding to register, with no confirmation required.
	  *   For this case, server will automatically login the user with the account information in request.
	  * 
	  * @return string
	  */
	 
	public function registerPostAction()
	{
		$result = array();
		
		$session = $this->_getSession();
		if ($session->isLoggedIn()) 
		{
			//$this->_redirect('*/*/');
			
			// If the user has been logined, we will take it as registered.
			$result['registered'] = 2;
			$result['user'] = $session->getCustomer()->getName();
						
			return;
		}
		$session->setEscapeMessages(true); // prevent XSS injection in user input
		if ($this->getRequest()->isPost()) 
		{
			$errors = array();

			if (!$customer = Mage::registry('current_customer')) 
			{
				$customer = Mage::getModel('customer/customer')->setId(null);
			}

			/* @var $customerForm Mage_Customer_Model_Form */
			$customerForm = Mage::getModel('customer/form');
			$customerForm->setFormCode('customer_account_create')
				->setEntity($customer);

			$customerData = $customerForm->extractData($this->getRequest());

			if ($this->getRequest()->getParam('is_subscribed', false)) 
			{
				$customer->setIsSubscribed(1);
			}

			/**
			 * Initialize customer group id
			 */
			$customer->getGroupId();

			if ($this->getRequest()->getPost('create_address')) 
			{
				/* @var $address Mage_Customer_Model_Address */
				$address = Mage::getModel('customer/address');
				/* @var $addressForm Mage_Customer_Model_Form */
				$addressForm = Mage::getModel('customer/form');
				$addressForm->setFormCode('customer_register_address')
					->setEntity($address);

				$addressData    = $addressForm->extractData($this->getRequest(), 'address', false);
				$addressErrors  = $addressForm->validateData($addressData);
				if ($addressErrors === true) 
				{
					$address->setId(null)
						->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
						->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false));
					$addressForm->compactData($addressData);
					$customer->addAddress($address);

					$addressErrors = $address->validate();
					if (is_array($addressErrors)) 
					{
						$errors = array_merge($errors, $addressErrors);
					}
				} 
				else 
				{
					$errors = array_merge($errors, $addressErrors);
				}
			}

			try 
			{
				$customerErrors = $customerForm->validateData($customerData);
				if ($customerErrors !== true) 
				{
					$errors = array_merge($customerErrors, $errors);
				} 
				else 
				{
					$customerForm->compactData($customerData);
					$customer->setPassword($this->getRequest()->getPost('password'));
					$customer->setConfirmation($this->getRequest()->getPost('confirmation'));
					$customerErrors = $customer->validate();
					if (is_array($customerErrors)) 
					{
						$errors = array_merge($customerErrors, $errors);
					}
				}

				$validationResult = count($errors) == 0;
				
				// If no errors occurred, save customer.
				if (true === $validationResult) 
				{
					$customer->save();

					Mage::dispatchEvent('customer_register_success',
						array('account_controller' => $this, 'customer' => $customer)
						);

					if ($customer->isConfirmationRequired()) 
					{
						$customer->sendNewAccountEmail(
							'confirmation',
							$session->getBeforeAuthUrl(),
							Mage::app()->getStore()->getId()
							);
						
						//$session->addSuccess($this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.', Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail())));
						//$this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure'=>true)));
						//return;
						
						$result['registered'] = 1;
						$result['detail'] = $this->__('Account confirmation is required. Please, check your email for the confirmation link. To resend the confirmation email please <a href="%s">click here</a>.', Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail()));
					} 
					else 
					{
						$result['registered'] = 2;
						$result['user'] = $customer->getName();
						
						$session->setCustomerAsLoggedIn($customer);
						
						//$url = $this->_welcomeCustomer($customer);
						//$this->_redirectSuccess($url);
						//return;
					}
				} 
				else 
				{
					$result['registered'] = 0;
					
					$session->setCustomerFormData($this->getRequest()->getPost());
					if (is_array($errors)) 
					{
						$errorMsgs = array();
						foreach ($errors as $errorMessage) 
						{
							//$session->addError($errorMessage);
							//$errorMsgs
							$errorMsgs[] = $errorMessage;
						}
						
						$result['detail'] = $errorMsgs;
					} 
					else 
					{
						//$session->addError($this->__('Invalid customer data'));
						$result['detail'] = $this->__('Invalid customer data');
					}
				}
			} 
			catch (Mage_Core_Exception $e) 
			{
				$result['registered'] = 0;
				
				$session->setCustomerFormData($this->getRequest()->getPost());
				if ($e->getCode() === Mage_Customer_Model_Customer::EXCEPTION_EMAIL_EXISTS) 
				{
					$url = Mage::getUrl('customer/account/forgotpassword');
					$message = $this->__('There is already an account with this email address. If you are sure that it is your email address, <a href="%s">click here</a> to get your password and access your account.', $url);				
					$session->setEscapeMessages(false);
				} 
				else 
				{
					$message = $e->getMessage();
				}
				//$session->addError($message);
				
				$result['detail'] = $message;
			} 
			catch (Exception $e) 
			{
				$session->setCustomerFormData($this->getRequest()->getPost())
					->addException($e, $this->__('Cannot save the customer.'));
					
				$result['registered'] = 0;
				$result['detail'] = $this->__('Cannot save the customer.');
			}
		}

		//$this->_redirectError(Mage::getUrl('*/*/create', array('_secure' => true)));
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
	}
}