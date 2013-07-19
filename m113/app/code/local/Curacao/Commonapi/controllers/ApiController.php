<?php
class Curacao_Commonapi_ApiController extends Mage_Core_Controller_Front_Action
{
    public function commercepackAction()
    {
		echo "AR-Commercepack Connection";

    }
	public function claimcouponAction()
	{
		$amt = 50;
		//Creating Coupon
		$cc = Mage::helper('core')->getRandomString(8);
  	    $ccode = strtoupper(strtolower($cc)).'50OFF';
		$time1 =  strtotime(date('Y-m-d')." -1 days");
		$time2 =  strtotime(date('Y-m-d')." +90 days");
		
		$data = array(
			'product_ids' => null,
			'name' => sprintf('Share and win $50'),
			'description' => 'CURCR3D1T',
			'is_active' => 1,
			'website_ids' => array(1),
			'customer_group_ids' => array(0,1,2,3),
			'coupon_type' => 2,
			'coupon_code' => $ccode,
			'uses_per_coupon' => 1,
			'uses_per_customer' => 1,
			'from_date' => date('Y-m-d',$time1),
			'to_date' => date('Y-m-d',$time2),
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
			'store_labels' => array('Share and win $50')
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
			$model->getConditions()->addCondition($conditions);	*/		  
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
}
