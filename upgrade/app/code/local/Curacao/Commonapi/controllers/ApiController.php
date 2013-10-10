<?php
class Curacao_Commonapi_ApiController extends Mage_Core_Controller_Front_Action
{
    public function commercepackAction()
    {
		$jd = json_decode($_REQUEST['json']);
		
		/*for($i=0;$i<sizeof($jd->items);$i++){
				
					$list['TEstLine'][] = array('Item_ID' => $jd->items[$i]->vendor_sku,
											  'Item_Name' => $jd->items[$i]->name,
											  'Qty' => (int)$jd->items[$i]->quantity,
											  'Price' =>$jd->items[$i]->price,
											  'Taxable' => 'Y' ) ;
				}
			$dateTime = explode(" ",$jd->created_date);
			
			$param = array('CreateDate' => $dateTime[0],
						   'CreateTime' => $dateTime[1],
						   'WebReference' => $jd->order_id,
						   'SubTotal' => $jd->subtotal,
						   'TaxAmount' => $jd->tax,
						   'DestinationZip' => $jd->destination_zip,
						   'ShipCharge' => $jd->shipping_revenue,
						   'Detail'=>$list
			);

		$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/test/asis/Main.asmx?WSDL');
		$ns = 'http://lacuracao.com/WebServices/AsIs/';
		
		//set the headers values
		$headerbody = array('UserName' => 'Asis', 
						  'Password' => 'LC8J71DYNN1MCK3Y46LT'); 
		//Create Soap Header.        
		$header = new SOAPHeader($ns, 'TAuthHeader', $headerbody);        
		//set the Headers of Soap Client. 
		$h = $proxy->__setSoapHeaders($header); 
		$credit = $proxy->CreateEstimateAsis($param,
										 "http://www.lacuracao.com/LAC-eComm-WebServices", 
										 "http://www.lacuracao.com/LAC-eComm-WebServices/WebCustomerApplication",
										 false, null , 'rpc', 'literal');  
		$re = $credit->CreateEstimateAsisResult;
		$final = explode(";",$re);
		//print_r($final);	
		$response = array(
				'success' => false,
				'error'=> true,
			);
		if(strtolower($final[0])=='error'){
			$response['success'] = false;
			$response['error'] = true;
			$response['externalOrderNumber'] = '';
			$response['message'] = $final[1];
		}elseif(strtolower($final[0])=='ok'){
			$response['success'] = true;
			$response['error'] = false;
			$response['externalOrderNumber'] = $final[1];
			$response['message'] = 'Estimate created successfully';
		}else{
			$response['success'] = false;
			$response['error'] = true;
			$response['externalOrderNumber'] = '';
			$response['message'] = $re;
		}*/
		$response['success'] = true;
		$response['error'] = false;
		$response['message'] = "AR Message with success or failure";
		
		$this->getResponse()->setBody(Zend_Json::encode($response));
		
		

		
    }
	public function addestimatenumberAction(){
		$orderId = Mage::app()->getRequest()->getParam('orderid');
		$estimate = Mage::app()->getRequest()->getParam('estimatenumber');
		$order = Mage::getModel('sales/order')->load($orderId);
		$order->setEstimatenumber($estimate);
		$response = array(
				'success' => false,
				'error'=> true,
			);
		try
		{
			$order->save();
			
			$response['success'] = true;
			$response['error'] = false;
			$response['message'] = $this->__('Estimate been added successfully.');        	
		}catch (Exception $e) {
			Mage::log($e->getMessage());
			
			$response['success'] = false;
			$response['error'] = true;
			$response['message'] = $this->__('Can not generate coupon code, please try again later.');        	
	
		}
		
		$this->getResponse()->setBody(Zend_Json::encode($response));
	}
	public function signupchekAction(){
		$response = array(
				'success' => false,
				'error'=> true,
			);
		
		if($_REQUEST['catId'] == '8' && !Mage::getSingleton('core/session')->getSignupbronto()){
				$response['success'] = true;
				$response['error'] = false;
				Mage::getSingleton('core/session')->setSignupbronto("Signup");
		}
		$this->getResponse()->setBody(Zend_Json::encode($response));
		
	}
	public function signupemailAction(){
		$email = $_REQUEST['email'];
		$subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
		$response = array(
				'success' => false,
				'error'=> true,
			);
			
		if (!$subscriber->getId()) {
			$subscriber->setStatus(Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED);
		   	$subscriber->setSubscriberEmail($email);
			//$subscriber->setSubscriberConfirmCode($subscriber->RandomSequence());
			$subscriber->setStoreId(Mage::app()->getStore()->getId());
			try {
				$subscriber->save();
				
				$tenoff = file_get_contents('http://www.icuracao.com/commonapi/api/signupcoupon');
				$tenoffcode = json_decode($tenoff);
			file_get_contents('http://app.bronto.com/public/?q=direct_add&fn=Public_DirectAddForm&id=acxhzmypejmnhsowqaqxwyyhyesgbcd&email='.$email.'&field1=Sign_Up_Lightbox,set,true&field2=Sign_Up_Coupon,set,'.$tenoffcode->code.'&list3=0bc603ec0000000000000000000000053fa7');
				$response['success'] = true;
				$response['error'] = false;
				$response['message'] = "Email Been Added";
				
			}
			catch (Exception $e) {
				throw new Exception($e->getMessage());
			}
			
		}else{
			$response['success'] = false;
			$response['error'] = true;
			$response['message'] = "Email Already Exist";
		}
		
		$this->getResponse()->setBody(Zend_Json::encode($response));
				
	}
	################Create 10% off Coupon for sign up ##############################
	public function signupcouponAction()
	{
		$customerGroupIds = Mage::getModel('customer/group')->getCollection()->getAllIds();
		$websitesId = Mage::getModel('core/website')->getCollection()->getAllIds();
	
		$cc = Mage::helper('core')->getRandomString(8);
  	    $couponCode = strtoupper(strtolower($cc));
	
		$model = Mage::getModel('salesrule/rule');
		$model->setName('10% off on signup');
		$model->setDescription('10% off on signup');
		$model->setFromDate();
		$model->setToDate(date('Y-m-d', strtotime('+2 days')));
		$model->setCouponType(2);
		$model->setCouponCode($couponCode);
		$model->setUsesPerCoupon(1);
		$model->setUsesPerCustomer(1);
		$model->setCustomerGroupIds($customerGroupIds);
		$model->setIsActive(1);
		$model->setConditionsSerialized('a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setActionsSerialized('a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setStopRulesProcessing(0);
		$model->setIsAdvanced(1);
		$model->setProductIds('');
		$model->setSortOrder(1);
		$model->setSimpleAction('by_percent');
		$model->setDiscountAmount('10');
		$model->setDiscountStep(0);
		$model->setSimpleFreeShipping(0);
		$model->setTimesUsed(0);
		$model->setIsRss(0);
		$model->setWebsiteIds($websitesId);
		$model->setStoreLabels(array('10% off on signup'));

		try {
			$model->save();
			$response = array(
				'success' => false,
				'error'=> true,
			);
			$response['success'] = true;
			$response['error'] = false;
			$response['code'] = $couponCode;
	
		
			
        //End Coupon thing
        
		} catch (Exception $e) {
			Mage::log($e->getMessage());
			$response['success'] = false;

			$response['error'] = true;
			$response['message'] = $this->__('Can not generate coupon code, please try again later.');        	
	
		}
		
		$this->getResponse()->setBody(Zend_Json::encode($response));
	}
	################ 10% off coupon End############################################# 
	
	################Create 10% off Coupon for Cart Abandon##############################
	public function cartabandoncouponAction()
	{
		$customerGroupIds = Mage::getModel('customer/group')->getCollection()->getAllIds();
		$websitesId = Mage::getModel('core/website')->getCollection()->getAllIds();
	
		$cc = Mage::helper('core')->getRandomString(8);
  	    $couponCode = strtoupper(strtolower($cc));
	
		$model = Mage::getModel('salesrule/rule');
		$model->setName('10% off');
		$model->setDescription('10% off on Cart Abandon');
		$model->setFromDate();
		$model->setToDate(date('Y-m-d', strtotime('+2 days')));
		$model->setCouponType(2);
		$model->setCouponCode($couponCode);
		$model->setUsesPerCoupon(1);
		$model->setUsesPerCustomer(1);
		$model->setCustomerGroupIds($customerGroupIds);
		$model->setIsActive(1);
		$model->setConditionsSerialized('a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setActionsSerialized('a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setStopRulesProcessing(0);
		$model->setIsAdvanced(1);
		$model->setProductIds('');
		$model->setSortOrder(1);
		$model->setSimpleAction('by_percent');
		$model->setDiscountAmount('10');
		$model->setDiscountStep(0);
		$model->setSimpleFreeShipping(0);
		$model->setTimesUsed(0);
		$model->setIsRss(0);
		$model->setWebsiteIds($websitesId);
		$model->setStoreLabels(array('10% off'));

		try {
			$model->save();
			$response = array(
				'success' => false,
				'error'=> true,
			);
			$response['success'] = true;
			$response['error'] = false;
			$response['code'] = $couponCode;
	
		
			
        //End Coupon thing
        
		} catch (Exception $e) {
			Mage::log($e->getMessage());
			$response['success'] = false;

			$response['error'] = true;
			$response['message'] = $this->__('Can not generate coupon code, please try again later.');        	
	
		}
		
		$this->getResponse()->setBody(Zend_Json::encode($response));
	}
	################ 10% off coupon End for Cart Abandon############################################# 
	
	public function claimcouponAction()
	{
		$customerGroupIds = Mage::getModel('customer/group')->getCollection()->getAllIds();
		$websitesId = Mage::getModel('core/website')->getCollection()->getAllIds();
	
		$cc = Mage::helper('core')->getRandomString(8);
  	    $couponCode = strtoupper(strtolower($cc));
	
		$model = Mage::getModel('salesrule/rule');
		$model->setName('Share and win $50');
		$model->setDescription('Share and win $50');
		$model->setFromDate();
		$model->setToDate(date('Y-m-d', strtotime('+90 days')));
		$model->setCouponType(2);
		$model->setCouponCode($couponCode);
		$model->setUsesPerCoupon(1);
		$model->setUsesPerCustomer(1);
		$model->setCustomerGroupIds($customerGroupIds);
		$model->setIsActive(1);
		$model->setConditionsSerialized('a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setActionsSerialized('a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setStopRulesProcessing(0);
		$model->setIsAdvanced(1);
		$model->setProductIds('');
		$model->setSortOrder(1);
		$model->setSimpleAction('cart_fixed');
		$model->setDiscountAmount('50');
		$model->setDiscountStep(0);
		$model->setSimpleFreeShipping(0);
		$model->setTimesUsed(0);
		$model->setIsRss(0);
		$model->setWebsiteIds($websitesId);
		$model->setStoreLabels(array('Share and win $50'));

		try {
			$model->save();
			$response = array(
				'success' => false,
				'error'=> true,
			);
			$response['success'] = true;
			$response['error'] = false;
			$response['code'] = $couponCode;
	
		
			
        //End Coupon thing
        
		} catch (Exception $e) {
			Mage::log($e->getMessage());
			$response['success'] = false;

			$response['error'] = true;
			$response['message'] = $this->__('Can not generate coupon code, please try again later.');        	
	
		}
		
		$this->getResponse()->setBody(Zend_Json::encode($response));
	}
	
	public function gwm25couponAction()
	{
		$customerGroupIds = Mage::getModel('customer/group')->getCollection()->getAllIds();
		$websitesId = Mage::getModel('core/website')->getCollection()->getAllIds();
	
		$cc = Mage::helper('core')->getRandomString(8);
  	    $couponCode = strtoupper(strtolower($cc));
	
		$model = Mage::getModel('salesrule/rule');
		$model->setName('$25 Off');
		$model->setDescription('$25 Off');
		$model->setFromDate();
		$model->setToDate(date('Y-m-d', strtotime('+2 days')));
		$model->setCouponType(2);
		$model->setCouponCode($couponCode);
		$model->setUsesPerCoupon(1);
		$model->setUsesPerCustomer(1);
		$model->setCustomerGroupIds($customerGroupIds);
		$model->setIsActive(1);
		$model->setConditionsSerialized('a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setActionsSerialized('a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setStopRulesProcessing(0);
		$model->setIsAdvanced(1);
		$model->setProductIds('');
		$model->setSortOrder(1);
		$model->setSimpleAction('cart_fixed');
		$model->setDiscountAmount('25');
		$model->setDiscountStep(0);
		$model->setSimpleFreeShipping(0);
		$model->setTimesUsed(0);
		$model->setIsRss(0);
		$model->setWebsiteIds($websitesId);
		$model->setStoreLabels(array('$25 Off'));

		try {
			$model->save();
			$response = array(
				'success' => false,
				'error'=> true,
			);
			$response['success'] = true;
			$response['error'] = false;
			$response['code'] = $couponCode;
	
		
			
        //End Coupon thing
        
		} catch (Exception $e) {
			Mage::log($e->getMessage());
			$response['success'] = false;

			$response['error'] = true;
			$response['message'] = $this->__('Can not generate coupon code, please try again later.');        	
	
		}
		
		$this->getResponse()->setBody(Zend_Json::encode($response));
	}
	
	public function promo10offcouponAction()
	{
		if($_SERVER['REMOTE_ADDR']=='206.170.79.99' || $_SERVER['REMOTE_ADDR']=='206.170.79.5'){
			$customerGroupIds = Mage::getModel('customer/group')->getCollection()->getAllIds();
			$websitesId = Mage::getModel('core/website')->getCollection()->getAllIds();
		
			$cc = Mage::helper('core')->getRandomString(8);
			$couponCode = strtoupper(strtolower($cc));
		
			$model = Mage::getModel('salesrule/rule');
			$model->setName('10&permil; Off');
			$model->setDescription('10&permil; Off by ana');
			$model->setFromDate();
			$model->setToDate(date('Y-m-d', strtotime('+2 days')));
			$model->setCouponType(2);
			$model->setCouponCode($couponCode);
			$model->setUsesPerCoupon(1);
			$model->setUsesPerCustomer(1);
			$model->setCustomerGroupIds($customerGroupIds);
			$model->setIsActive(1);
			$model->setConditionsSerialized('a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
			$model->setActionsSerialized('a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
			$model->setStopRulesProcessing(0);
			$model->setIsAdvanced(1);
			$model->setProductIds('');
			$model->setSortOrder(1);
			$model->setSimpleAction('by_percent');
			$model->setDiscountAmount('10');
			$model->setDiscountStep(0);
			$model->setSimpleFreeShipping(0);
			$model->setTimesUsed(0);
			$model->setIsRss(0);
			$model->setWebsiteIds($websitesId);
			$model->setStoreLabels(array('10&permil; Off'));
	
			try {
				$model->save();
				$response = array(
					'success' => false,
					'error'=> true,
				);
				$response['success'] = true;
				$response['error'] = false;
				$response['code'] = $couponCode;
		
			
				
			//End Coupon thing
			
			} catch (Exception $e) {
				Mage::log($e->getMessage());
				$response['success'] = false;
	
				$response['error'] = true;
				$response['message'] = $this->__('Can not generate coupon code, please try again later.');        	
		
			}
		}else{
			$response['success'] = false;
			$response['error'] = true;
			$response['message'] = 'Access Denied.';        	
		}
		$this->getResponse()->setBody(Zend_Json::encode($response));
		
	}
	
/*	public function signup10offAction()
	{
		$amt = 10;
		//Creating Coupon
		$cc = Mage::helper('core')->getRandomString(8);
  	    $ccode = strtoupper(strtolower($cc));
		$time1 =  strtotime(date('Y-m-d')." -1 days");
		$time2 =  strtotime(date('Y-m-d')." +3 days");
		
		$data = array(
			'product_ids' => null,
			'name' => sprintf('Registration 10 off'),
			'description' => 'CURCR3D1T',
			'is_active' => 1,
			'website_ids' => array(1),
			'customer_group_ids' => array(0,1,2,3),
			'coupon_type' => 2,
			'coupon_code' => $ccode,
			'uses_per_coupon' => 1,
			'uses_per_customer' => 1,
			'from_date' => '',
			'to_date' => date('Y-m-d', strtotime('+2 days')),
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
			'simple_action' => 'by_percent',
			'discount_amount' => $amt,
			'discount_qty' => 0,
			'discount_step' => null,
			'apply_to_shipping' => 1,
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
			'store_labels' => array('Free Shipping - Registration')
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
			/*$conditions = Mage::getModel('salesrule/rule_condition_product_combine')
						  ->setType('salesrule/rule_condition_address')
						  ->setAttribute('base_subtotal')
						  ->setOperator('>=')
						  ->setValue('499');
			$model->getConditions()->addCondition($conditions);	
			$model->save();
		}
			
		// Apply discount
		$code = $couponCode = $ccode;
		
				
		$response = array(
			'success' => false,
			'error'=> true,
		);
		
        if (!empty($couponCode)) {
                $response['success'] = true;
                $response['error'] = false;
                $response['code'] = $code;

        } else {
            $response['success'] = false;

            $response['error'] = true;
            $response['message'] = $this->__('Can not generate coupon code, please try again later.');        	
        }
        //End Coupon thing
        $this->getResponse()->setBody(Zend_Json::encode($response));
	}
*/	
	public function createCoupon10offAction()
	{
	
		$customerGroupIds = Mage::getModel('customer/group')->getCollection()->getAllIds();
		$websitesId = Mage::getModel('core/website')->getCollection()->getAllIds();
	
		$cc = Mage::helper('core')->getRandomString(8);
  	    $couponCode = strtoupper(strtolower($cc));
	
		$model = Mage::getModel('salesrule/rule');
		$model->setName('Registration 10 off');
		$model->setDescription('Register and get 10&permil; off, first purchase');
		$model->setFromDate();
		$model->setToDate(date('Y-m-d', strtotime('+3 days')));
		$model->setCouponType(2);
		$model->setCouponCode($couponCode);
		$model->setUsesPerCoupon(1);
		$model->setUsesPerCustomer(1);
		$model->setCustomerGroupIds($customerGroupIds);
		$model->setIsActive(1);
		$model->setConditionsSerialized('a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setActionsSerialized('a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setStopRulesProcessing(0);
		$model->setIsAdvanced(1);
		$model->setProductIds('');
		$model->setSortOrder(1);
		$model->setSimpleAction('by_percent');
		$model->setDiscountAmount('10');
		$model->setDiscountStep(0);
		$model->setSimpleFreeShipping(0);
		$model->setTimesUsed(0);
		$model->setIsRss(0);
		$model->setWebsiteIds($websitesId);
		$model->setStoreLabels(array('Registration 10 off'));

		try {
			$model->save();
			$response = array(
				'success' => false,
				'error'=> true,
			);
			$response['success'] = true;
			$response['error'] = false;
			$response['code'] = $couponCode;
	
		
			
        //End Coupon thing
        
		} catch (Exception $e) {
			Mage::log($e->getMessage());
			$response['success'] = false;

			$response['error'] = true;
			$response['message'] = $this->__('Can not generate coupon code, please try again later.');        	
	
		}
		
		$this->getResponse()->setBody(Zend_Json::encode($response));
	}
	
	public function createCouponfreeshipAction()
	{
	
		$customerGroupIds = Mage::getModel('customer/group')->getCollection()->getAllIds();
		$websitesId = Mage::getModel('core/website')->getCollection()->getAllIds();
	
		$cc = Mage::helper('core')->getRandomString(8);
  	    $couponCode = strtoupper(strtolower($cc));
	
		$model = Mage::getModel('salesrule/rule');
		$model->setName('Register_FreeShipping');
		$model->setDescription('Register and get free shipping on first purchase');
		$model->setFromDate();
		$model->setToDate(date('Y-m-d', strtotime('+6 days')));
		$model->setCouponType(2);
		$model->setCouponCode($couponCode);
		$model->setUsesPerCoupon(1);
		$model->setUsesPerCustomer(1);
		$model->setCustomerGroupIds($customerGroupIds);
		$model->setIsActive(1);
		$model->setConditionsSerialized('a:6:{s:4:\"type\";s:32:\"salesrule/rule_condition_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setActionsSerialized('a:6:{s:4:\"type\";s:40:\"salesrule/rule_condition_product_combine\";s:9:\"attribute\";N;s:8:\"operator\";N;s:5:\"value\";s:1:\"1\";s:18:\"is_value_processed\";N;s:10:\"aggregator\";s:3:\"all\";}');
		$model->setStopRulesProcessing(0);
		$model->setIsAdvanced(1);
		$model->setProductIds('');
		$model->setSortOrder(1);
		$model->setSimpleAction('by_percent');
		$model->setDiscountAmount('0');
		$model->setDiscountStep(0);
		$model->setSimpleFreeShipping(0);
		$model->setTimesUsed(0);
		$model->setIsRss(0);
		$model->setWebsiteIds($websitesId);
		$model->setStoreLabels(array('Free Shipping'));

		try {
			$model->save();
			$response = array(
				'success' => false,
				'error'=> true,
			);
			$response['success'] = true;
			$response['error'] = false;
			$response['code'] = $couponCode;
	
		
			
        //End Coupon thing
        
		} catch (Exception $e) {
			Mage::log($e->getMessage());
			$response['success'] = false;

			$response['error'] = true;
			$response['message'] = $this->__('Can not generate coupon code, please try again later.');        	
	
		}
		
		$this->getResponse()->setBody(Zend_Json::encode($response));
	}
	
}
