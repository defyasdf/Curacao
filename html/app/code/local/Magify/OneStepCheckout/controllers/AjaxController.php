<?php
class Magify_OneStepCheckout_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function add_extra_productAction()
    {
        $helper = Mage::helper('onestepcheckout/extraproducts');
        $product_id = $this->getRequest()->getPost('product_id');
        $remove = $this->getRequest()->getPost('remove', false);

        if($helper->isValidExtraProduct($product_id)) {

            if(!$remove)    {
                /* Add product to cart if it doesn't exist */
                $product = Mage::getModel('catalog/product')->load($product_id);
                $cart = Mage::getSingleton('checkout/cart');
                $cart->addProduct($product);
                $cart->save();
            }
            else    {
                $items = Mage::helper('checkout/cart')->getCart()->getItems();
                foreach($items as $item)    {
                    if($item->getProduct()->getId() == $product_id) {
                        Mage::helper('checkout/cart')->getCart()->removeItem($item->getId())->save();
                    }
                }

            }
        }
        $this->loadLayout(false);
        $this->renderLayout();
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    public function indexAction()
    {
        $resource = Mage::getResourceModel('sales/order_collection');

        if(method_exists($resource, 'getEntity'))   {
            echo 'Is using EAV';
        }
        else {
            echo 'Not using EAV';
        }

        die();

        var_dump($resource->getEntity());
        var_dump(get_class_methods($resource->getEntity()));
        var_dump($resource);

        die();

        var_dump(get_class_methods($resource));




        echo get_class($collection);
        echo '<br>';
        echo get_class($sales);

        var_dump(get_class_methods($collection));

        var_dump(get_class_methods($sales));
        var_dump($sales);

        die('<br><br>ajaxcontroller!');

        $this->loadLayout();
        $this->renderLayout();
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    protected function _isEmailRegistered($email)
    {
        $model = Mage::getModel('customer/customer');
        $model->setWebsiteId(Mage::app()->getStore()->getWebsiteId())->loadByEmail($email);

        if($model->getId() == NULL)    {
            return false;
        }

        return true;
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    public function add_couponAction()
    {
        $quote = $this->_getOnepage()->getQuote();
        $couponCode = (string)$this->getRequest()->getParam('code');

        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }

        $response = array(
            'success' => false,
            'error'=> false,
            'message' => false,
        );

        try {

            $quote->getShippingAddress()->setCollectShippingRates(true);
            $quote->setCouponCode(strlen($couponCode) ? $couponCode : '')
            ->collectTotals()
            ->save();

            if ($couponCode) {
            
                if ($couponCode == $quote->getCouponCode()) {
                    $response['success'] = true;
                    $response['message'] = $this->__('Coupon code "%s" was applied successfully.', Mage::helper('core')->htmlEscape($couponCode));
                }
                else {
                
                    $response['success'] = false;
                    $response['error'] = true;
                    $response['message'] = $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->htmlEscape($couponCode));
                }
            } else {
                $response['success'] = true;
                $response['message'] = $this->__('Coupon code was canceled successfully.');
            }


        }
        catch (Mage_Core_Exception $e) {
            $response['success'] = false;
            $response['error'] = true;
            $response['message'] = $e->getMessage();
        }
        catch (Exception $e) {
            $response['success'] = false;
            $response['error'] = true;
            $response['message'] = $this->__('Can not apply coupon code.');
        }

        if(!Mage::helper('onestepcheckout')->isEnterprise() && Mage::helper('customer')->isLoggedIn()){
            $customerBalanceBlock = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance', array('template'=>'onestepcheckout/customerbalance/payment/additional.phtml'));
            $this->getLayout()->getBlock('choose-payment-method')
            ->append($customerBalanceBlock);
            ;
        }

        $html = $this->getLayout()
        ->createBlock('checkout/onepage_shipping_method_available')
        ->setTemplate('onestepcheckout/shipping_method.phtml')
        ->toHtml();

        $response['shipping_method'] = $html;


        $html = $this->getLayout()
        ->createBlock('checkout/onepage_payment_methods')
        ->setTemplate('onestepcheckout/payment_method.phtml');

        if(Mage::helper('onestepcheckout')->isEnterprise()){
            $giftcardScripts = $this->getLayout()->createBlock('enterprise_giftcardaccount/checkout_onepage_payment_additional', 'giftcardaccount_scripts', array('template'=>'onestepcheckout/giftcardaccount/onepage/payment/scripts.phtml'));
            $html->append($giftcardScripts);
        }

        $response['payment_method'] = $html->toHtml();

          // Add updated totals HTML to the output
        $html = $this->getLayout()
        ->createBlock('onestepcheckout/summary')
        ->setTemplate('onestepcheckout/summary.phtml')
        ->toHtml();

        $response['summary'] = $html;

        $this->getResponse()->setBody(Zend_Json::encode($response));
    }
	//--------------------------------------------------------------------------------------------------------------------------------
	//
	//--------------------------------------------------------------------------------------------------------------------------------
	public function removedpAction(){
		Mage::getSingleton('core/session')->unsCuracacaodp();
		Mage::getSingleton('core/session')->unsAttempt();
		echo Mage::getSingleton('core/session')->getCuracacaodp();
	}
	//--------------------------------------------------------------------------------------------------------------------------------
	//Add Address
	//--------------------------------------------------------------------------------------------------------------------------------
	public function addaddressAction(){
		$c_id = Mage::getSingleton('customer/session')->getId();
		
		$_custom_address = array ('firstname'=>$_REQUEST['fname'],'lastname' => $_REQUEST['lname'],'street' => array ('0' => $_REQUEST['street1'],'1' => $_REQUEST['street2'],),'city' => $_REQUEST['city'],'region_id' => $_REQUEST['state_id'],'postcode' => $_REQUEST['zip'],'country_id' => $_REQUEST['country'],'telephone' => $_REQUEST['tel'],);
			$customAddress = Mage::getModel('customer/address');
			//$customAddress = new Mage_Customer_Model_Address();
			$customAddress->setData($_custom_address)->setCustomerId($c_id)->setIsDefaultBilling('1')->setIsDefaultShipping('1')->setSaveInAddressBook('1');	
		try {
           $customAddress->save();
		    $result = array('success'=>true,'address_id'=>$customAddress->getId());
        }
        catch (Mage_Core_Exception $e) {
//           $response['message'] = $e->getMessage();
		    $result = array('success'=>false,'message'=>$e->getMessage());
			
      	  
			
        }
		  $this->getResponse()->setBody(Zend_Json::encode($result));
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------
	//Add Address
	//--------------------------------------------------------------------------------------------------------------------------------
	public function addshippingaddressAction(){
		$c_id = Mage::getSingleton('customer/session')->getId();
		
		$_custom_address = array ('firstname'=>$_REQUEST['fname'],'lastname' => $_REQUEST['lname'],'street' => array ('0' => $_REQUEST['street1'],'1' => $_REQUEST['street2'],),'city' => $_REQUEST['city'],'region_id' => $_REQUEST['state_id'],'postcode' => $_REQUEST['zip'],'country_id' => $_REQUEST['country'],'telephone' => $_REQUEST['tel'],);
			$customAddress = Mage::getModel('customer/address');
			//$customAddress = new Mage_Customer_Model_Address();
			$customAddress->setData($_custom_address)->setCustomerId($c_id)->setIsDefaultShipping('1')->setSaveInAddressBook('1');	
		try {
           $customAddress->save();
		    $result = array('success'=>true,'address_id'=>$customAddress->getId());
        }
        catch (Mage_Core_Exception $e) {
//           $response['message'] = $e->getMessage();
		    $result = array('success'=>false,'message'=>$e->getMessage());
			
      	  
			
        }
		  $this->getResponse()->setBody(Zend_Json::encode($result));
	}
	
	//--------------------------------------------------------------------------------------------------------------------------------
	//Remove Address
	//--------------------------------------------------------------------------------------------------------------------------------
	public function deleteAction()
    {
        $addressId = $this->getRequest()->getParam('id', false);
		$reload = '0';
        if ($addressId) {
            $address = Mage::getModel('customer/address')->load($addressId);
			
			$customer = Mage::getSingleton('customer/session')->getCustomer();
			$add_ids = array();
			foreach ($customer->getAddresses() as $address)
			 {	
				$add_ids[] = $address->getId();
			 }
			 $other_ids = array();
			 for($i=0;$i<sizeof($add_ids);$i++){
				if($add_ids[$i]!=$_REQUEST['add_id']){
					$other_ids[] = $add_ids[$i];
				}		
			}
				
			 $customerAddressId = $customer->getDefaultBilling();
			 $customershipAddressId = $customer->getDefaultShipping();
			 if(sizeof($other_ids)>0){
				 if($customerAddressId == $addressId){
					$customAddress = Mage::getModel('customer/address')->load($other_ids[0]);
					$customAddress->setIsDefaultBilling('1')->setIsDefaultShipping('1')->setSaveInAddressBook('1');						
					$customAddress->save();
				}elseif($customershipAddressId == $addressId){
					$customAddress = Mage::getModel('customer/address')->load($other_ids[0]);
					$customAddress->setIsDefaultShipping('1')->setSaveInAddressBook('1');						
					$customAddress->save();
				}
				
			}else{
				$reload = 1;
			}
			// Validate address_id <=> customer_id
            if ($address->getCustomerId() != $this->_getSession()->getCustomerId()) {
                //$this->_getSession()->addError($this->__('The address does not belong to this customer.'));
                //$this->getResponse()->setRedirect(Mage::getUrl('*/*/index'));
				
				$result = array('success'=>false,'message'=>'The address does not belong to this customer.');
				
                return;
            }

            try {
                $address->delete();
				$result = array('success'=>true,'reload'=>$reload);
              //  $this->_getSession()->addSuccess($this->__('The address has been deleted.'));
            } catch (Exception $e){
   //             $this->_getSession()->addException($e, $this->__('An error occurred while deleting the address.'));
				$result = array('success'=>false,'message'=>'An error occurred while deleting the address.');
            }
        }
       $this->getResponse()->setBody(Zend_Json::encode($result));
    }
	
	
	//--------------------------------------------------------------------------------------------------------------------------------
	//Update the cart using Ajax
	//--------------------------------------------------------------------------------------------------------------------------------
	public function updatecartAction(){
		$cartHelper = Mage::helper('checkout/cart');
		$items = $cartHelper->getCart()->getItems();
		foreach ($items as $item) {
			if ($item->getProduct()->getId() == $_GET['pro_id']) {
				if( $_GET['qty'] == 0 ){
					$cartHelper->getCart()->removeItem($item->getItemId())->save();
				}
				else{
					$item->setQty($_GET['qty']);
					$cartHelper->getCart()->save();
				}
				break;
			}
		}
	}

	//--------------------------------------------------------------------------------------------------------------------------------
	//
	//--------------------------------------------------------------------------------------------------------------------------------
	public function curacao_creditAction(){

		//$grandtotal = Mage::getSingleton('checkout/cart')->getQuote()->getGrandTotal();
		
		$getcart = Mage::getSingleton('checkout/cart')->getQuote();
		
		$_couponcode = $this->_getOnepage()->getQuote()->getCouponCode(); 
		//$_quote = $this->getQuote();
		$_coupon = Mage::getModel('salesrule/coupon')->load($_couponcode);
		$cpn = $getcart->getCouponCode();
		if($cpn!=''){
		 $ccp = explode('_',$cpn);
		// print_r($ccp);
		 if($ccp[0]=='SYS' && $ccp[2]=='CR3D1T'){
				$couponCode = '';
				/* END This part is my extra, just to load our coupon for this specific customer */
				 
				Mage::getSingleton('checkout/cart')
					->getQuote()
					->getShippingAddress()
					->setCollectShippingRates(true);
				 
				Mage::getSingleton('checkout/cart')
					->getQuote()
					->setCouponCode(strlen($couponCode) ? $couponCode : '')
					->collectTotals()
					->save();
		 }
		}
		
		$grandtotal = $getcart->getGrandTotal();
		
		
		$cardnumber = (string)$this->getRequest()->getParam('cardnumber');
		//53145246
		$amt = file_get_contents( Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB).'/credit/checkcredit.php?cust_num='.$cardnumber.'&amount='.$grandtotal);		
//		$amt = file_get_contents('http://data.icuracao.com/credit/checkcredit.php?cust_num='.$cardnumber.'&amount='.$grandtotal);
		
		//Create a coupon
		$amt = explode(",",$amt);
		$credit = $amt[0];
		$dp = $amt[1];
	   
		if($credit < $grandtotal){
			$discount = (int)$credit - (int)$dp;
		}else{
			$discount = $grandtotal - $dp;
		}
		    
	    /*if($credit >= $grandtotal){
			$amount_to_pay = $grandtotal - $dp;
	    }
	    else{
			$amount_to_pay = $credit - $dp;
	    }*/
			 
		//$ccode = Mage::helper('core')->getRandomString(16);	//get a random Curacao Coupon code
		
		 $cc = Mage::helper('core')->getRandomString(7);
  	     $ccode = 'SYS_'.strtolower($cc).'_CR3D1T';
		
		$data = array(
			'product_ids' => null,
			'name' => sprintf('AUTO_GENERATION CUSTOMER_%s - Curacao Coupon', Mage::getSingleton('customer/session')->getCustomerId()),
			'description' => 'CURCR3D1T',
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
			'discount_amount' => $discount,
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
		$code = $couponCode = $ccode;
		
		/* END This part is my extra, just to load our coupon for this specific customer */ 
		Mage::getSingleton('checkout/cart')
			->getQuote()
			->getShippingAddress()
			->setCollectShippingRates(true);

			
		// End Creating coupon
		//$code = $this->getRequest()->getParam('code', false);
		// $remove = $this->getRequest()->getParam('remove', false);

		
		$response = array(
			'success' => false,
			'error'=> true,
			'message' => $this->__('Cannot apply Gift Card, please try again later.'),
		);
		
        if (!empty($couponCode)) {
            try {
                
				Mage::getSingleton('checkout/cart')
					->getQuote()
					->setCouponCode(strlen($couponCode) ? $couponCode : '')
					->collectTotals()
					->save();

                $response['success'] = true;
                $response['error'] = false;
                $response['message'] = $this->__('Curacao Credit Applied successfully.', Mage::helper('core')->htmlEscape($code));
                $response['curc'] = true;

            } catch (Mage_Core_Exception $e) {
                Mage::dispatchEvent('enterprise_giftcardaccount_add', array('status' => 'fail', 'code' => $code));

                $response['success'] = false;
                $response['error'] = true;
                $response['message'] = $e->getMessage();
                $response['curc'] = true;

            } catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addException(
                    $e,
                    $this->__('Cannot pay with Curacao Credit Card, please try again later.')
                );

                $response['success'] = false;
                $response['error'] = true;
                $response['message'] = $this->__('Cannot pay with Curacao Credit Card, please try again later.');
                $response['curc'] = true;
            }
        } else {
            $response['success'] = false;
            $response['error'] = true;
            $response['message'] = $this->__('Cannot pay with Curacao Credit Card, please try again later.');        	
        }
        
        $this->getResponse()->setBody(Zend_Json::encode($response));
	}
	################ Add Coupon #################################################
	public function createautosignupcouponAction(){
		
		
		//Get Products from cart
		
		$amt = 100;
		//Creating Coupon
		$cc = Mage::helper('core')->getRandomString(7);
  	    $ccode = strtoupper(strtolower($cc)).'_SIGNUP100';
		
		$data = array(
			'product_ids' => null,
			'name' => sprintf('Sign up and get $100 off'),
			'description' => 'CURCR3D1T',
			'is_active' => 1,
			'website_ids' => array(1),
			'customer_group_ids' => array(0,1,2,3),
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
			'store_labels' => array('Buy One Get One 50%')
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
		$code = $couponCode = $ccode;
		
		/* END This part is my extra, just to load our coupon for this specific customer */ 
		Mage::getSingleton('checkout/cart')
			->getQuote()
			->getShippingAddress()
			->setCollectShippingRates(true);

			
		// End Creating coupon
		//$code = $this->getRequest()->getParam('code', false);
		// $remove = $this->getRequest()->getParam('remove', false);

		
		$response = array(
			'success' => false,
			'error'=> true,
			'message' => $this->__('Cannot apply Gift Card, please try again later.'),
		);
		
        if (!empty($couponCode)) {
            try {
                
				Mage::getSingleton('checkout/cart')
					->getQuote()
					->setCouponCode(strlen($couponCode) ? $couponCode : '')
					->collectTotals()
					->save();

                $response['success'] = true;
                $response['error'] = false;
                $response['message'] = $this->__('Coupon code applied successfully.', Mage::helper('core')->htmlEscape($code));
                $response['curc'] = true;

            } catch (Mage_Core_Exception $e) {
                Mage::dispatchEvent('enterprise_giftcardaccount_add', array('status' => 'fail', 'code' => $code));

                $response['success'] = false;
                $response['error'] = true;
                $response['message'] = $e->getMessage();
                $response['curc'] = true;

            } catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addException(
                    $e,
                    $this->__('Can not add coupon code, please try again later.')
                );

                $response['success'] = false;
                $response['error'] = true;
                $response['message'] = $this->__('Can not generate coupon code, please try again later.');
                $response['curc'] = true;
            }
        } else {
            $response['success'] = false;

            $response['error'] = true;
            $response['message'] = $this->__('Can not generate coupon code, please try again later.');        	
        }
        //End Coupon thing
	
        $this->getResponse()->setBody(Zend_Json::encode($response));
	}
	################ End Add Coupon #############################################
	
	
	################ Add Coupon #################################################
	public function createautocouponAction(){
		
		//Check the condition
			$rule = Mage::getModel('salesrule/rule')->load(5168); 
			$conditions = $rule->getConditions()->asArray();

			if($rule->getIsActive()){
				$start_ts = strtotime($rule->getfrom_date());
				$end_ts = strtotime($rule->getto_date());
				$user_ts = strtotime(date('Y-m-d'));
			
				if((($user_ts >= $start_ts) && ($user_ts <= $end_ts))){		
					
					$condistion = $conditions['conditions'][0]['conditions'];
					$cat = array();
					for($i = 0; $i<sizeof($condistion);$i++){
						$cat[] = $condistion[$i]['value'];
					}
				}
			}
		//End Checking condition
		
		//Get Products from cart
		$session = Mage::getSingleton('checkout/session');
		$output = "";
		$price = array();
		
		$totalQuantity = Mage::getModel('checkout/cart')->getQuote()->getItemsQty();
		
		Mage::getSingleton('core/session')->setTotalqty($totalQuantity);
		
		foreach ($session->getQuote()->getAllItems() as $item) {
			//$product[$item->getProductId()] = $item->getQty();
			$product = Mage::getModel('catalog/product')->load($item->getProductId());
			$pcat = $product->getCategoryIds();
			
			if(in_array($cat[0],$pcat)){
				//echo 'I m here';
				for($j=0;$j<$item->getQty();$j++){
					$price[] = number_format($product->getPrice(), 2);
				}
			}
		}
		//End getting products in the cart
	if(sizeof($price)>1){	
		$amt = min($price)/2;
		//Creating Coupon
		$cc = Mage::helper('core')->getRandomString(7);
  	    $ccode = 'SYS_'.strtolower($cc).'_'.$_POST['coupon'];
		
		$data = array(
			'product_ids' => null,
			'name' => sprintf('Buy One Get Half Off'),
			'description' => 'CURCR3D1T',
			'is_active' => 1,
			'website_ids' => array(1),
			'customer_group_ids' => array(0,1,2,3),
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
			'store_labels' => array('Buy One Get One 50%')
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
		$code = $couponCode = $ccode;
		
		/* END This part is my extra, just to load our coupon for this specific customer */ 
		Mage::getSingleton('checkout/cart')
			->getQuote()
			->getShippingAddress()
			->setCollectShippingRates(true);

			
		// End Creating coupon
		//$code = $this->getRequest()->getParam('code', false);
		// $remove = $this->getRequest()->getParam('remove', false);

		
		$response = array(
			'success' => false,
			'error'=> true,
			'message' => $this->__('Cannot apply Gift Card, please try again later.'),
		);
		
        if (!empty($couponCode)) {
            try {
                
				Mage::getSingleton('checkout/cart')
					->getQuote()
					->setCouponCode(strlen($couponCode) ? $couponCode : '')
					->collectTotals()
					->save();

                $response['success'] = true;
                $response['error'] = false;
                $response['message'] = $this->__('Coupon code applied successfully.', Mage::helper('core')->htmlEscape($code));
                $response['curc'] = true;

            } catch (Mage_Core_Exception $e) {
                Mage::dispatchEvent('enterprise_giftcardaccount_add', array('status' => 'fail', 'code' => $code));

                $response['success'] = false;
                $response['error'] = true;
                $response['message'] = $e->getMessage();
                $response['curc'] = true;

            } catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addException(
                    $e,
                    $this->__('Can not add coupon code, please try again later.')
                );

                $response['success'] = false;
                $response['error'] = true;
                $response['message'] = $this->__('Can not generate coupon code, please try again later.');
                $response['curc'] = true;
            }
        } else {
            $response['success'] = false;

            $response['error'] = true;
            $response['message'] = $this->__('Can not generate coupon code, please try again later.');        	
        }
        //End Coupon thing
	}
        $this->getResponse()->setBody(Zend_Json::encode($response));
	}
	################ End Add Coupon #############################################
	################ Current Cart Contents ######################################
	public function getcartcontentAction(){
		
	//	echo $_GET['pdata'];
		
		$pdata = unserialize($_POST['pdata']);
		$result = array(
            'success' => false
        );
		$cart = Mage::getSingleton('checkout/cart');
        foreach($pdata as $pid=>$qty){
			$product = Mage::getModel('catalog/product')->load($pid);
			$cart->addProduct($product, array('qty' => $qty, 'product_id' => $product->getId()));  
		}
		try{
	    	$cart->save();
		}catch(Exception $ex){
		  		$result['error'] = $ex->getMessage();
		  }	
		  if(!isset($result['error'])){
				$result['success'] = true;
			}
		  
		$this->getResponse()->setBody(Zend_Json::encode($result));
	}
	################ End Current Cart Contents #################################
	
	####################### Registration Function ###############################
	public function newcustomerAction(){
		
		$firstname = $this->getRequest()->getPost('firstname', false);
        $lastname = $this->getRequest()->getPost('lastname', false);
		$email = $this->getRequest()->getPost('email', false);
        $password = $this->getRequest()->getPost('password', false);
		$confirm = $this->getRequest()->getPost('confirmation', false);
        $signup = $this->getRequest()->getPost('is_subscribed', false);
		$session = Mage::getSingleton('customer/session');
		$quote = Mage::getModel('sales/quote');
		
		$result = array(
            'success' => false
        );
		
		if($password != $confirm){
			$result['error'] = 'Please make sure your passwords match';
		}
		
		$customer = Mage::getModel('customer/customer');
		$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
		$customer->loadByEmail($email);
		
		// -----------------------------------------------------------------------------------------------
		// Check if the email exist on the system.
		// -----------------------------------------------------------------------------------------------
		if($customer->getId()) {
			$result['error'] = 'Our record shows that customer with the email already registered';
		}
		
		 if(!isset($result['error'])) {
			// Get Current Quote Item and append to login
			$session = Mage::getSingleton('checkout/session');
			$output = "";
			$product  = array();
			foreach ($session->getQuote()->getAllItems() as $item) {
				$product[$item->getProductId()] = $item->getQty();
			}
		
			$customer->setEmail($email); 			//set user data
			$customer->setFirstname($firstname);
			$customer->setLastname($lastname);
			$customer->setPassword($password);
			$customer->setSubscription($signup);
			$customerReg = 0;
			try {
			  $customer->save();
			  $customer->setConfirmation(null);
			  $customer->save(); 
			  $customer->sendNewAccountEmail();
			  $session = $this->_getSession();	
			 // $session->login($email, $psswd);  
			 
			 $customer = Mage::getModel('customer/customer')->setWebsiteId(1)->loadByEmail($email);
			 $quote->assignCustomer($customer);
			 
			 $customerReg = 1;
			 $customerLogin = 0;
			 
			 if($signup){
				 
				    Mage::getModel('newsletter/subscriber')->setImportMode(true)->subscribe($email);
					# get just generated subscriber
					$subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
					# change status to "subscribed" and save
					$subscriber->setStatus(Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED);
					$subscriber->save();
	
			}
			 
			  try {
				$session->login($email, $password);
				$customerLogin = 1;
				
			  } catch(Exception $e)   {
				$result['error'] = $e->getMessage();
			  } 
			} catch(Exception $ex){
		  		$result['error'] = $ex->getMessage();
		   }			
		}
		if(!isset($result['error'])) {
			$result['success'] = true;
			$result['product'] = serialize($product);
		}
		
		
        $this->getResponse()->setBody(Zend_Json::encode($result));
		    
		
			
	}
	
	####################### End Registration Function ########################## 
	
   	//--------------------------------------------------------------------------------------------------------------------------------
    public function add_giftcardAction(){

        $response = array(
            'success' => false,
            'error'=> true,
            'message' => $this->__('Cannot apply Gift Card, please try again later.'),
        );

        $code = $this->getRequest()->getParam('code', false);
        $remove = $this->getRequest()->getParam('remove', false);

        if (!empty($code) && empty($remove)) {
            try {
                Mage::getModel('enterprise_giftcardaccount/giftcardaccount')
                    ->loadByCode($code)
                    ->addToCart();

                $response['success'] = true;
                $response['error'] = false;
                $response['message'] = $this->__('Gift Card "%s" was added successfully.', Mage::helper('core')->htmlEscape($code));

            } catch (Mage_Core_Exception $e) {
                Mage::dispatchEvent('enterprise_giftcardaccount_add', array('status' => 'fail', 'code' => $code));

                $response['success'] = false;
                $response['error'] = true;
                $response['message'] = $e->getMessage();

            } catch (Exception $e) {
                Mage::getSingleton('checkout/session')->addException(
                    $e,
                    $this->__('Cannot apply Gift Card, please try again later.')
                );

                $response['success'] = false;
                $response['error'] = true;
                $response['message'] = $this->__('Cannot apply Gift Card, please try again later.');

            }
        }

        if(!empty($remove)){
            $codes = $this->_getOnepage()->getQuote()->getGiftCards();
            if(!empty($codes)){
                $codes = unserialize($codes);
            } else {
                $codes = array();
            }
            $response['message'] = $this->__('Cannot remove Gift Card, please try again later.');
            $messageCodes = array();
            foreach($codes as $value){
                try {
                    Mage::getModel('enterprise_giftcardaccount/giftcardaccount')
                        ->loadByCode($value['c'])
                        ->removeFromCart();
                    $messageCodes[] = $value['c'];
                    $response['success'] = true;
                    $response['error'] = false;
                    $response['message'] = $this->__('Gift Card "%s" was removed successfully.', Mage::helper('core')->htmlEscape(implode(', ',$messageCodes)));

                } catch (Mage_Core_Exception $e) {

                    $response['success'] = false;
                    $response['error'] = true;
                    $response['message'] = $e->getMessage();

                } catch (Exception $e) {
                    Mage::getSingleton('checkout/session')->addException(
                        $e,
                        $this->__('Cannot remove Gift Card, please try again later.')
                    );

                    $response['success'] = false;
                    $response['error'] = true;
                    $response['message'] = $this->__('Cannot remove Gift Card, please try again later.');

                }
            }
        }



        // Add updated totals HTML to the output
        $html = $this->getLayout()
        ->createBlock('onestepcheckout/summary')
        ->setTemplate('onestepcheckout/summary.phtml')
        ->toHtml();

        $response['summary'] = $html;

        $html = $this->getLayout()
        ->createBlock('checkout/onepage_shipping_method_available')
        ->setTemplate('onestepcheckout/shipping_method.phtml')
        ->toHtml();

        $response['shipping_method'] = $html;

        $html = $this->getLayout()
        ->createBlock('checkout/onepage_payment_methods')
        ->setTemplate('onestepcheckout/payment_method.phtml');

        if(Mage::helper('onestepcheckout')->isEnterprise()){
            $giftcardScripts = $this->getLayout()->createBlock('enterprise_giftcardaccount/checkout_onepage_payment_additional', 'giftcardaccount_scripts', array('template'=>'onestepcheckout/giftcardaccount/onepage/payment/scripts.phtml'));
            $html->append($giftcardScripts);
        }

        $response['payment_method'] = $html->toHtml();

        $this->getResponse()->setBody(Zend_Json::encode($response));
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    public function check_emailAction()
    {
        $validator = new Zend_Validate_EmailAddress();
        $email = $this->getRequest()->getPost('email', false);

        $data = array('result'=>'invalid');


        if($email && $email != '')  {
            if(!$validator->isValid($email))    {

            }
            else    {

                // Valid email, check for existance
                if($this->_isEmailRegistered($email))   {
                    $data['result'] = 'exists';
                }
                else    {
                    $data['result'] = 'clean';
                }
            }
        }

        $this->getResponse()->setBody(Zend_Json::encode($data));
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    public function forgotPasswordAction()
    {
        $email = $this->getRequest()->getPost('email', false);

        if (!Zend_Validate::is($email, 'EmailAddress')) {
            $result = array('success'=>false);
        }
        else    {

            $customer = Mage::getModel('customer/customer')
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
            ->loadByEmail($email);

            if ($customer->getId()) {
                try {
                    $newPassword = $customer->generatePassword();
                    $customer->changePassword($newPassword, false);
                    $customer->sendPasswordReminderEmail();
                    $result = array('success'=>true);
                }
                catch (Exception $e){
                    $result = array('success'=>false, 'error'=>$e->getMessage());
                }
            }
            else {
                $result = array('success'=>false, 'error'=>'notfound');
            }
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));
    }
	//-------------------------------------------------------------------------------------------------------------------------------
	#############################Log Out Function###########################################
	
	public function logoutAction()
    {
        $session = Mage::getSingleton('customer/session');
		$session->logout();
		 $result['success'] = true;
		
		$this->getResponse()->setBody(Zend_Json::encode($result));
	}
	
	############################End Log out function #######################################
	
	//--------------------------------------------------------------------------------------------------------------------------------
	
    public function loginAction()
    {
        $username = $this->getRequest()->getPost('onestepcheckout_username', false);
        $password = $this->getRequest()->getPost('onestepcheckout_password', false);
        $session = Mage::getSingleton('customer/session');

        $result = array(
            'success' => false
        );

        if($username && $password) {
            try {
                $session->login($username, $password);
            } catch(Exception $e)   {
                $result['error'] = $e->getMessage();
            }

            if(!isset($result['error']))    {

                //$quote = Mage::getSingleton('checkout/type_onepage')->getQuote();
                //$quote->collectTotals()->save();


                $result['success'] = true;
				$result['user'] = $session->getCustomer()->getId();
            }
        }
        else    {
            $result['error'] = $this->__('Please enter a username and password.');
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));

    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    public function save_billingAction()
    {
        $helper = Mage::helper('onestepcheckout/checkout');

        $billing_data = $this->getRequest()->getPost('billing', array());
        $shipping_data = $this->getRequest()->getPost('shipping', array());
        $customerAddressId = $this->getRequest()->getPost('billing_address_id', false);
        $shippingAddressId = $this->getRequest()->getPost('shipping_address_id', false);

        $billing_data = $helper->load_exclude_data($billing_data);
        $shipping_data = $helper->load_exclude_data($shipping_data);

        if(Mage::helper('customer')->isLoggedIn() && $helper->differentShippingAvailable()){
            if(!empty($customerAddressId)){
                $billingAddress = Mage::getModel('customer/address')->load($customerAddressId);
                if(is_object($billingAddress) && $billingAddress->getCustomerId() ==  Mage::helper('customer')->getCustomer()->getId()){
                    $billing_data = array_merge($billing_data, $billingAddress->getData());
                }
            }
            if(!empty($shippingAddressId)){
                $shippingAddress = Mage::getModel('customer/address')->load($shippingAddressId);
                if(is_object($shippingAddress) && $shippingAddress->getCustomerId() ==  Mage::helper('customer')->getCustomer()->getId()){
                    $shipping_data = array_merge($shipping_data, $shippingAddress->getData());
                }
            }
        }

        if(!empty($billing_data['use_for_shipping'])) {
           $shipping_data = $billing_data;
        }

        // set customer tax/vat number for further usage
        if (!empty($billing_data['taxvat'])) {
            $this->_getOnepage()->getQuote()->setCustomerTaxvat($billing_data['taxvat']);
            $this->_getOnepage()->getQuote()->setTaxvat($billing_data['taxvat']);
            $this->_getOnepage()->getQuote()->getBillingAddress()->setTaxvat($billing_data['taxvat']);
        } else {
            $this->_getOnepage()->getQuote()->setCustomerTaxvat('');
            $this->_getOnepage()->getQuote()->setTaxvat('');
            $this->_getOnepage()->getQuote()->getBillingAddress()->setTaxvat('');
        }

        $this->_getOnepage()->getQuote()->getBillingAddress()->addData($billing_data)->implodeStreetAddress()->setCollectShippingRates(true);
        //$this->_getOnepage()->getQuote()->getShippingAddress()->addData($shipping_data)->implodeStreetAddress()->setCollectShippingRates(true);


        //$this->_getOnepage()->getQuote()->getShippingAddress()->collectTotals();

        $paymentMethod = $this->getRequest()->getPost('payment_method', false);
        $selectedMethod = $this->_getOnepage()->getQuote()->getPayment()->getMethod();

        $store = $this->_getOnepage()->getQuote() ? $this->_getOnepage()->getQuote()->getStoreId() : null;
        $methods = $helper->getActiveStoreMethods($store, $this->_getOnepage()->getQuote());

        if($paymentMethod && !empty($methods) && !in_array($paymentMethod, $methods)){
            $paymentMethod = false;
        }

        if(!$paymentMethod && $selectedMethod && in_array($selectedMethod, $methods)){
             $paymentMethod = $selectedMethod;
        }

        if($this->_getOnepage()->getQuote()->isVirtual()) {
            $this->_getOnepage()->getQuote()->getBillingAddress()->setPaymentMethod(!empty($paymentMethod) ? $paymentMethod : null);
        } else {
            $this->_getOnepage()->getQuote()->getShippingAddress()->setPaymentMethod(!empty($paymentMethod) ? $paymentMethod : null);
        }

        try {
            if($paymentMethod){
                $this->_getOnepage()->getQuote()->getPayment()->getMethodInstance();
            }
        } catch (Exception $e) {
        }

        $result = $this->_getOnepage()->saveBilling($billing_data, $customerAddressId);

        if($helper->differentShippingAvailable()) {
            if(empty($billing_data['use_for_shipping'])) {
                $shipping_result = $helper->saveShipping($shipping_data, $shippingAddressId);
            }else {
                $shipping_result = $helper->saveShipping($billing_data, $customerAddressId);
            }
        }

        $shipping_method = $this->getRequest()->getPost('shipping_method', false);

        if(!empty($shipping_method)) {
           $helper->saveShippingMethod($shipping_method);
        }

        $this->_getOnepage()->getQuote()->setTotalsCollectedFlag(false)->collectTotals();

        $this->loadLayout(false);

        if(Mage::helper('onestepcheckout')->isEnterprise() && Mage::helper('customer')->isLoggedIn()){

            $customerBalanceBlock = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance', array('template'=>'onestepcheckout/customerbalance/payment/additional.phtml'));
            $customerBalanceBlockScripts = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance_scripts', array('template'=>'onestepcheckout/customerbalance/payment/scripts.phtml'));

            $rewardPointsBlock = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.points', array('template'=>'onestepcheckout/reward/payment/additional.phtml', 'before' => '-'));
            $rewardPointsBlockScripts = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.scripts', array('template'=>'onestepcheckout/reward/payment/scripts.phtml', 'after' => '-'));

            $this->getLayout()->getBlock('choose-payment-method')
            ->append($customerBalanceBlock)
            ->append($customerBalanceBlockScripts)
            ->append($rewardPointsBlock)
            ->append($rewardPointsBlockScripts)
            ;

        }

        if(Mage::helper('onestepcheckout')->isEnterprise()){
            $giftcardScripts = $this->getLayout()->createBlock('enterprise_giftcardaccount/checkout_onepage_payment_additional', 'giftcardaccount_scripts', array('template'=>'onestepcheckout/giftcardaccount/onepage/payment/scripts.phtml'));
            $this->getLayout()->getBlock('choose-payment-method')
            ->append($giftcardScripts);
        }

        $this->renderLayout();

    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    public function paymentrefreshAction()
    {
        $payment_method = $this->getRequest()->getPost('payment_method');
        $helper = Mage::helper('onestepcheckout/checkout');
        if($payment_method != '')   {
            try {
                $payment = $this->getRequest()->getPost('payment', array());
                $payment['method'] = $payment_method;
                $this->_getOnepage()->getQuote()->getPayment()->setMethod($payment['method'])->getMethodInstance();
                //$payment_result = $this->_getOnepage()->savePayment($payment);
                $helper->savePayment($payment);
            }
            catch(Exception $e) {
                //die('Error: ' . $e->getMessage());
                // Silently fail for now
            }
        }

        $this->loadLayout(false);

        if(Mage::helper('onestepcheckout')->isEnterprise() && Mage::helper('customer')->isLoggedIn()){

            $customerBalanceBlock = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance', array('template'=>'onestepcheckout/customerbalance/payment/additional.phtml'));
            $customerBalanceBlockScripts = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance_scripts', array('template'=>'onestepcheckout/customerbalance/payment/scripts.phtml'));

            $rewardPointsBlock = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.points', array('template'=>'onestepcheckout/reward/payment/additional.phtml', 'before' => '-'));
            $rewardPointsBlockScripts = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.scripts', array('template'=>'onestepcheckout/reward/payment/scripts.phtml', 'after' => '-'));

            $this->getLayout()->getBlock('choose-payment-method')
            ->append($customerBalanceBlock)
            ->append($customerBalanceBlockScripts)
            ->append($rewardPointsBlock)
            ->append($rewardPointsBlockScripts)
            ;
        }

        $this->renderLayout();
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    public function set_methods_separateAction()
    {
        $helper = Mage::helper('onestepcheckout/checkout');

        $shipping_method = $this->getRequest()->getPost('shipping_method');
        $old_shipping_method = $this->_getOnepage()->getQuote()->getShippingAddress()->getShippingMethod();

        if($shipping_method != '' && $shipping_method != $old_shipping_method)  {
            //$result = $this->_getOnepage()->saveShippingMethod($shipping_method);
            // Use our helper instead
            $helper->saveShippingMethod($shipping_method);
        }
        //$this->_getOnepage()->getQuote()->getShippingAddress()->collectTotals();

        $paymentMethod = $this->getRequest()->getPost('payment_method', false);
        $selectedMethod = $this->_getOnepage()->getQuote()->getPayment()->getMethod();

        $store = $this->_getOnepage()->getQuote() ? $this->_getOnepage()->getQuote()->getStoreId() : null;
        $methods = $helper->getActiveStoreMethods($store, $this->_getOnepage()->getQuote());

        if($paymentMethod && !empty($methods) && !in_array($paymentMethod, $methods)){
            $paymentMethod = false;
        }

        if(!$paymentMethod && $selectedMethod && in_array($selectedMethod, $methods)){
             $paymentMethod = $selectedMethod;
        }

        try {
            $payment = $this->getRequest()->getPost('payment', array());
            //$payment = array();
            if(!empty($paymentMethod)){
                $payment['method'] = $paymentMethod;
            }
            //$payment_result = $this->_getOnepage()->savePayment($payment);
            $helper->savePayment($payment);
        }
        catch(Exception $e) {
            //die('Error: ' . $e->getMessage());
            // Silently fail for now
        }
        $this->_getOnepage()->getQuote()->collectTotals()->save();
        $this->loadLayout(false);

        if(Mage::helper('onestepcheckout')->isEnterprise() && Mage::helper('customer')->isLoggedIn()){

            $customerBalanceBlock = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance', array('template'=>'onestepcheckout/customerbalance/payment/additional.phtml'));
            $customerBalanceBlockScripts = $this->getLayout()->createBlock('enterprise_customerbalance/checkout_onepage_payment_additional', 'customerbalance_scripts', array('template'=>'onestepcheckout/customerbalance/payment/scripts.phtml'));

            $rewardPointsBlock = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.points', array('template'=>'onestepcheckout/reward/payment/additional.phtml', 'before' => '-'));
            $rewardPointsBlockScripts = $this->getLayout()->createBlock('enterprise_reward/checkout_payment_additional', 'reward.scripts', array('template'=>'onestepcheckout/reward/payment/scripts.phtml', 'after' => '-'));

            $this->getLayout()->getBlock('choose-payment-method')
            ->append($customerBalanceBlock)
            ->append($customerBalanceBlockScripts)
            ->append($rewardPointsBlock)
            ->append($rewardPointsBlockScripts)
            ;
        }

        if(Mage::helper('onestepcheckout')->isEnterprise()){
            $giftcardScripts = $this->getLayout()->createBlock('enterprise_giftcardaccount/checkout_onepage_payment_additional', 'giftcardaccount_scripts', array('template'=>'onestepcheckout/giftcardaccount/onepage/payment/scripts.phtml'));
            $this->getLayout()->getBlock('choose-payment-method')
            ->append($giftcardScripts);
        }

        $this->renderLayout();
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    public function set_methodsAction()
    {
        $helper = Mage::helper('onestepcheckout/checkout');
        $shipping_method = $this->getRequest()->getPost('shipping_method');

        if($shipping_method != '')  {
            //$result = $this->_getOnepage()->saveShippingMethod($shipping_method);
            $helper->saveShippingMethod($shipping_method);
        }

        $payment_method = $this->getRequest()->getPost('payment_method');

        if($payment_method != '')   {
            try {
                $payment = $this->getRequest()->getPost('payment', array());
                $payment['method'] = $payment_method;
                //$payment_result = $this->_getOnepage()->savePayment($payment);
                $helper->savePayment($payment);
            }
            catch(Exception $e) {
                //die('Error: ' . $e->getMessage());
                // Silently fail for now
            }
        }

        //$this->_getOnepage()->getQuote()->collectTotals()->save();

        $this->loadLayout(false);
        $this->renderLayout();
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    public function set_payment_methodAction()
    {
        $payment_method = $this->getRequest()->getPost('payment_method');
        $payment = array('method' => $payment_method);
        $result = $this->_getOnepage()->savePayment($payment);

        $this->loadLayout(false);
        $this->renderLayout();
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    public function set_shipping_methodAction()
    {
        $shipping_method = $this->getRequest()->getPost('shipping_method');
        $result = $this->_getOnepage()->saveShippingMethod($shipping_method);

        $this->loadLayout(false);
        $this->renderLayout();
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    protected function _getOnepage()
    {
        return Mage::getSingleton('checkout/type_onepage');
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    public function registerAction()
    {
        if ($this->_getSession()->isLoggedIn()) {
            $this->_redirect('*/*/');
            return;
        }
        if ($this->getRequest()->isPost()) {
            $errors = array();



            if (!$customer = Mage::registry('current_customer')) {
                $customer = Mage::getModel('customer/customer')->setId(null);
            }

            $lastOrderId = $this->_getOnepage()->getCheckout()->getLastOrderId();
            $order = Mage::getModel('sales/order')->load($lastOrderId);
            $billing = $order->getBillingAddress();

            $customer->setData('firstname', $billing->getFirstname());
            $customer->setData('lastname', $billing->getLastname());
            $customer->setData('email', $order->getCustomerEmail());


            foreach (Mage::getConfig()->getFieldset('customer_account') as $code=>$node) {
                //echo $code . ' -> ' . $node . '<br/>';
                if ($node->is('create') && ($value = $this->getRequest()->getParam($code)) !== null) {
                    $customer->setData($code, $value);
                }
            }

            // print_r($customer->toArray());


            if ($this->getRequest()->getParam('is_subscribed', false)) {
                $customer->setIsSubscribed(1);
            }

            /**
             * Initialize customer group id
             */
            $customer->getGroupId();

            if ($this->getRequest()->getPost('create_address')) {
                $address = Mage::getModel('customer/address')
                ->setData($this->getRequest()->getPost())
                ->setIsDefaultBilling($this->getRequest()->getParam('default_billing', false))
                ->setIsDefaultShipping($this->getRequest()->getParam('default_shipping', false))
                ->setId(null);
                $customer->addAddress($address);

                $errors = $address->validate();
                if (!is_array($errors)) {
                    $errors = array();
                }
            }

            $result = array(
                'success' => false,
                'message' => false,
                'error' => false,
            );


            try {
                $validationCustomer = $customer->validate();
                if (is_array($validationCustomer)) {
                    $errors = array_merge($validationCustomer, $errors);
                }
                $validationResult = count($errors) == 0;

                //var_dump($validationResult);

                if (true === $validationResult) {

                    $customer->save();

                    $result['success'] = true;

                    if ($customer->isConfirmationRequired()) {

                        $customer->sendNewAccountEmail('confirmation', $this->_getSession()->getBeforeAuthUrl());
                        $this->_getSession()->addSuccess($this->__('Account confirmation is required. Please, check your e-mail for confirmation link. To resend confirmation email please <a href="%s">click here</a>.',
                        Mage::helper('customer')->getEmailConfirmationUrl($customer->getEmail())
                        ));

                        $result['message'] = 'email_confirmation';

                        //$this->_redirectSuccess(Mage::getUrl('*/*/index', array('_secure'=>true)));
                        //return;
                    }
                    else {
                        $this->_getSession()->setCustomerAsLoggedIn($customer);
                        $url = $this->_welcomeCustomer($customer);

                        $result['message'] = 'customer_logged_in';
                    }

                    // Add the last order to this account
                    $order->setCustomerId($customer->getId());
                    $order->setCustomerIsGuest(false);
                    $order->setCustomerGroupId($customer->getGroupId());
                    $order->save();

                    // Dispatch event to trigger downloadable products
                    /*
                    $items = $order->getItemsCollection();

                    foreach($items as $item)    {
                    Mage::dispatchEvent('sales_order_item_save_after', array('item'=>$item));
                    } */



                } else {
                    $this->_getSession()->setCustomerFormData($this->getRequest()->getPost());
                    if (is_array($errors)) {
                        foreach ($errors as $errorMessage) {
                            //$this->_getSession()->addError($errorMessage);
                        }

                        $result['error'] = 'validation_failed';
                        $result['errors'] = $errors;

                    }
                    else {
                        //$this->_getSession()->addError($this->__('Invalid customer data'));
                        $result['error'] = 'invalid_customer_data';
                    }
                }
            }
            catch (Mage_Core_Exception $e) {

                $result['error'] = $e->getMessage();

                //$this->_getSession()->addError($e->getMessage())
                //    ->setCustomerFormData($this->getRequest()->getPost());
            }
            catch (Exception $e) {

                $result['error'] = $e->getMessage();

                //$this->_getSession()->setCustomerFormData($this->getRequest()->getPost())
                //    ->addException($e, $this->__('Can\'t save customer'));
            }
        }

        $this->getResponse()->setBody(Zend_Json::encode($result));

        //
        //$result['error'] = 'redirect_to_create'
        ///die('About to redirect to create');

        //$this->_redirectError(Mage::getUrl('*/*/create', array('_secure'=>true)));
    }
   	//--------------------------------------------------------------------------------------------------------------------------------
    protected function _welcomeCustomer(Mage_Customer_Model_Customer $customer, $isJustConfirmed = false)
    {
        $this->_getSession()->addSuccess($this->__('Thank you for registering with %s', Mage::app()->getStore()->getName()));

        $customer->sendNewAccountEmail($isJustConfirmed ? 'confirmed' : 'registered');

        $successUrl = Mage::getUrl('*/*/index', array('_secure'=>true));
        if ($this->_getSession()->getBeforeAuthUrl()) {
            $successUrl = $this->_getSession()->getBeforeAuthUrl(true);
        }
        return $successUrl;
    }
}
