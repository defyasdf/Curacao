<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Shipping
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */


/**
 * Flat rate shipping model
 *
 * @category   Mage
 * @package    Mage_Shipping
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Mage_Shipping_Model_Carrier_Flatrate
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{

    protected $_code = 'flatrate';
    protected $_isFixed = true;

    /**
     * Enter description here...
     *
     * @param Mage_Shipping_Model_Rate_Request $data
     * @return Mage_Shipping_Model_Rate_Result
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
    	$cat = array();
    	
        if (!$this->getConfigFlag('active')) {
            return false;
        }
        $freeBoxes = 0;
        if ($request->getAllItems()) {
			$_helper = Mage::helper('catalog/output');
			$custom_ship=0;
            foreach ($request->getAllItems() as $item) {

                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }
				$productId = $item->getProduct()->getId();
				$cur_fproduct = Mage::getModel('catalog/product')->load($productId);
				
				if($_helper->productAttribute($cur_fproduct, $cur_fproduct->getShprate(), 'shprate')!='Domestic'){
					$shipRate = 0;
					// New Free Shipping Code
						$rule = Mage::getModel('salesrule/rule')->load(5166); 
						$conditions = $rule->getConditions()->asArray();

						if($rule->getIsActive()){
							$start_ts = strtotime($rule->getfrom_date());
							$end_ts = strtotime($rule->getto_date());
							$user_ts = strtotime(date('Y-m-d'));
						
							if((($user_ts >= $start_ts) && ($user_ts <= $end_ts))){		
								
								$condistion = $conditions['conditions'][0]['conditions'];
								
								for($i = 0; $i<sizeof($condistion);$i++){
									$cat[] = $condistion[$i]['value'];
								}
							}
						}

						if(sizeof($cat)>0){
							$cat_ids = $cur_fproduct->getCategoryIds();
							$shipRate = ($item->getQty())*($_helper->productAttribute($cur_fproduct, $cur_fproduct->getShprate(), 'shprate'));
							for($j=0;$j<sizeof($cat);$j++){
								if(in_array($cat[$j],$cat_ids)){
									$shipRate = 0;	
								}
							}
						}else{
							$shipRate = ($item->getQty())*($_helper->productAttribute($cur_fproduct, $cur_fproduct->getShprate(), 'shprate'));
						}
						
						
						$coupon_code = Mage::getSingleton('checkout/session')->getQuote()->getCouponCode();
						if($coupon_code){
							$oCoupon = Mage::getModel('salesrule/coupon')->load($coupon_code, 'code');
							$oRule = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
							$cData = $oRule->getData();
							if($cData['name']=='Register_FreeShipping'){
								$sstart_ts = strtotime($oRule->getfrom_date());
								$send_ts = strtotime($oRule->getto_date());
								$suser_ts = strtotime(date('Y-m-d'));
								if((($user_ts >= $start_ts) && ($user_ts <= $end_ts))){		
									$shipRate = 0;
								}
							}
							
							
							// For Free Shipping comdition
							
							
							$conditions = $oRule->getConditions()->asArray();
							if($oRule->getIsActive()&&$oRule->getsimple_free_shipping()){
								$start_ts = strtotime($oRule->getfrom_date());
								$end_ts = strtotime($oRule->getto_date());
								$user_ts = strtotime(date('Y-m-d'));
							
								if((($user_ts >= $start_ts) && ($user_ts <= $end_ts))){		
									
									$condistion = $conditions['conditions'][0]['conditions'];
									
									for($i = 0; $i<sizeof($condistion);$i++){
										$cat[] = $condistion[$i]['value'];
									}
								}
							}
	
							if(sizeof($cat)>0){
								$cat_ids = $cur_fproduct->getCategoryIds();
								$shipRate = ($item->getQty())*($_helper->productAttribute($cur_fproduct, $cur_fproduct->getShprate(), 'shprate'));
								for($j=0;$j<sizeof($cat);$j++){
									if(in_array($cat[$j],$cat_ids)){
										$shipRate = 0;	
									}
								}
							
							}else{
								$shipRate = ($item->getQty())*($_helper->productAttribute($cur_fproduct, $cur_fproduct->getShprate(), 'shprate'));
							}
							
							
							
							
						}
						// End Free Shipping Code					

					$custom_ship +=	$shipRate;		
					
				}else{
					$custom_ship += 0;	
				}
				
				
                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $freeBoxes += $item->getQty() * $child->getQty();
                        }
                    }
                } elseif ($item->getFreeShipping()) {
                    $freeBoxes += $item->getQty();
                }
            }
        }
        $this->setFreeBoxes($freeBoxes);

        $result = Mage::getModel('shipping/rate_result');
        if ($this->getConfigData('type') == 'O') { // per order
            $shippingPrice = $this->getConfigData('price');
        } elseif ($this->getConfigData('type') == 'I') { // per item
            $shippingPrice = ($request->getPackageQty() * $this->getConfigData('price')) - ($this->getFreeBoxes() * $this->getConfigData('price'));
        } else {
            $shippingPrice = false;
        }

		if( isset($custom_ship)){
			$shippingPrice = (float)$custom_ship;
		} else {
			$custom_ship = 0;
		}
		
        if ($shippingPrice !== false) {
            $method = Mage::getModel('shipping/rate_result_method');

            $method->setCarrier('flatrate');
            $method->setCarrierTitle($this->getConfigData('title'));

            $method->setMethod('flatrate');
            $method->setMethodTitle($this->getConfigData('name'));

            if ($request->getFreeShipping() === true || $request->getPackageQty() == $this->getFreeBoxes()) {
               // $shippingPrice = '0.00';
            }


            $method->setPrice($shippingPrice);
            $method->setCost($shippingPrice);

            $result->append($method);
        }

        return $result;
    }

    public function getAllowedMethods()
    {
        return array('flatrate'=>$this->getConfigData('name'));
    }
	
	

}