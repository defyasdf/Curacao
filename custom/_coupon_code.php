<?php

ini_set('max_execution_time', 600);
ini_set('memory_limit', '1024M');

require '../app/Mage.php';
$app = Mage::app('');

$server = '192.168.100.121';
$user = 'curacaodata';
$pass = 'curacaodata';
$db = 'icuracaoproduct';


$link = mysql_connect($server,$user,$pass);

mysql_select_db($db,$link);

$sql = "select * from coupon_codes limit 7,3000";
$result = mysql_query($sql);
while($row = mysql_fetch_array($result)){

//$labels = array('0'=>'10% off','1'=>'10% off','2'=>'10% off','3'=>'10% off');
//$shoppingCartPriceRule = Mage::getModel('salesrule/rule');

$name = $row['name']; // name of  Rule
$websiteId = 1;
$customerGroupId = '0,1,2,3';
$actionType = 'by_percent'; // discount by percentage(other options are: by_fixed, cart_fixed, buy_x_get_y)
$discount = 10; // percentage discount
 
$shoppingCartPriceRule = Mage::getModel('salesrule/rule');
 
$shoppingCartPriceRule
    ->setName($name)
    ->setDescription('')
    ->setCouponType('2')
	->setCouponCode($row['code'])
	->setUsesPerCoupon(1)
    ->setUsesPerCustomer(1)
	->setIsActive(1)
    ->setWebsiteIds(array($websiteId))
    ->setCustomerGroupIds(array(0,1,2,3))
    ->setFromDate('2012-11-08')
    ->setToDate('2013-01-31')
    ->setSortOrder('')
    ->setSimpleAction($actionType)
    ->setDiscountAmount($discount)
    ->setStopRulesProcessing(0);
 
$skuCondition = Mage::getModel('salesrule/rule_condition_address')
                    ->setType('salesrule/rule_condition_address')
                    ->setAttribute('base_subtotal')
                    ->setOperator('>=')
                    ->setValue('50');
 
try {
    $shoppingCartPriceRule->getConditions()->addCondition($skuCondition);
    $shoppingCartPriceRule->save();
    
	/*$rule_id = $shoppingCartPriceRule->getId();
    $shoppingCartPriceRule = Mage::getResourceModel('salesrule/rule');
    $shoppingCartPriceRule->saveStoreLabels($rule_id,$labels);*/
	
	$shoppingCartPriceRule->applyAll();
	 

} catch (Exception $e) {
    Mage::getSingleton('core/session')->addError(Mage::helper('catalog')->__($e->getMessage()));
    //return;
}

}



?>
