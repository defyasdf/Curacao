<?php

require_once 'Mage/Checkout/controllers/OnepageController.php';

class Curacao_Credit_OnepageController extends Mage_Checkout_OnepageController
{
    public function doSomestuffAction()
    {
		if(true) {
			$result['update_section'] = array(
            	'name' => 'payment-method',
                'html' => $this->_getPaymentMethodsHtml()
			);					
		}
    	else {
			$result['goto_section'] = 'shipping';
		}		
    }    
    /**
     * Shipping method save action
     */
    public function saveShippingMethodAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost('shipping_method', '');
            $result = $this->getOnepage()->saveShippingMethod($data);
            /*
            $result will have erro data if shipping method is empty
            */
            if(!$result) {
                Mage::dispatchEvent('checkout_controller_onepage_save_shipping_method',
                        array('request'=>$this->getRequest(),
                            'quote'=>$this->getOnepage()->getQuote()));
                $this->getOnepage()->getQuote()->collectTotals();
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));

                $this->loadLayout('checkout_onepage_credit');

                $result['goto_section'] = 'credit';
                
                
            }
            $this->getOnepage()->getQuote()->collectTotals()->save();
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
        }
    }
    
      
    public function saveCreditAction()
    {

        $this->_expireAjax();

        if ($this->getRequest()->isPost()) {

                        $_curacao_credit_customerNumber = $this->getRequest()->getPost('credit_customer_number');
                        $_curacao_credit_customerDiscount = $this->getRequest()->getPost('credit_customer_discount');
			
							Mage::getSingleton('core/session')->setCuracaoCreditCustomerNumber($_curacao_credit_customerNumber);                        		Mage::getSingleton('core/session')->setCuracaoCreditCustomerDiscount($_curacao_credit_customerDiscount);
			
			$result = array();
	    if($_curacao_credit_customerDiscount != "" and Mage::getSingleton('customer/session')->getCustomerId() != "")
            	$this->createCRule($_curacao_credit_customerDiscount);
           // $redirectUrl = $this->getOnePage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            //if (!$redirectUrl) {
                $result['goto_section'] = 'payment';
                $result['update_section'] = array(
                    'name' => 'payment-method',
                    'html' => $this->_getPaymentMethodsHtml()
                );

           // }

            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }

            $this->getResponse()->setBody(Zend_Json::encode($result));
        }
    }   

   public function createCRule($amt){
   $amt = explode(",",$amt);
   $credit = $amt[0];
   $dp = $amt[1];
   $grandtotal = Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal();
   
   
   if($credit == 0){
		$amount_to_pay = $grandtotal - $credit;
		$amount_to_pay = $amount_to_pay + $dp;
		$amount_to_pay = $grandtotal - $amount_to_pay;
   }
   else{
		$amount_to_pay = $grandtotal - $dp;
   }
     
   
   $ccode = Mage::helper('core')->getRandomString(16);
    $data = array(
	    'product_ids' => null,
	    'name' => sprintf('AUTO_GENERATION CUSTOMER_%s - Curacao Coupon', Mage::getSingleton('customer/session')->getCustomerId()),
	    'description' => null,
	    'is_active' => 1,
	    'website_ids' => array(1),
	    'customer_group_ids' => array(1),
	    'coupon_type' => 2,
	    'coupon_code' => $ccode,
	    'uses_per_coupon' => 1,
	    'uses_per_customer' => 1,
	    'from_date' => null,
	    'to_date' => null,
	    'sort_order' => null,
	    'is_rss' => 1,
	    'rule' => array(
	        'conditions' => array(
	            array(
	                'type' => 'salesrule/rule_condition_combine',
	                'aggregator' => 'all',
	                'value' => 1,
	                'new_child' => null
	            )
	        )
	    ),
	    'simple_action' => 'cart_fixed',
	    'discount_amount' => $amount_to_pay,
	    'discount_qty' => 0,
	    'discount_step' => null,
	    'apply_to_shipping' => 0,
	    'simple_free_shipping' => 0,
	    'stop_rules_processing' => 0,
	    'rule' => array(
	        'actions' => array(
	            array(
	                'type' => 'salesrule/rule_condition_product_combine',
	                'aggregator' => 'all',
	                'value' => 1,
	                'new_child' => null
	            )
	        )
	    ),
	    'store_labels' => array('Curacao Credit')
	);
	 
	$model = Mage::getModel('salesrule/rule');
	$data = $this->_filterDates($data, array('from_date', 'to_date'));
	 
	$validateResult = $model->validateData(new Varien_Object($data));
	 
	if ($validateResult == true) {
	 
	    if (isset($data['simple_action']) && $data['simple_action'] == 'by_percent'
	            && isset($data['discount_amount'])) {
	        $data['discount_amount'] = min(100, $data['discount_amount']);
	    }
	 
	    if (isset($data['rule']['conditions'])) {
	        $data['conditions'] = $data['rule']['conditions'];
	    }
	 
	    if (isset($data['rule']['actions'])) {
	        $data['actions'] = $data['rule']['actions'];
	    }
	 
	    unset($data['rule']);
	 
	    $model->loadPost($data);
	 
	    $model->save();
	}
	
	// Apply discount
	$couponCode = $ccode;
	/* END This part is my extra, just to load our coupon for this specific customer */
	 
	 Mage::getSingleton('checkout/cart')
	    ->getQuote()
	    ->getShippingAddress()
	    ->setCollectShippingRates(true); 
	
	/*
	
		amount to pay  = grandtotal - credit
		amount to pay = amount to pay + dp

		discount = amount to pay :fixed
	*/
	
	
	Mage::getSingleton('checkout/cart')
	    ->getQuote()
	    ->setCouponCode(strlen($couponCode) ? $couponCode : '')
	    ->collectTotals()
	    ->save();
   }
}