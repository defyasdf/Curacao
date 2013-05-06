<?php
	
require_once("app/Mage.php");
Mage::app('default');

/*$sql = "DELETE FROM `core_resource` WHERE `code`='magify_bannermanager_setup';";
$connection = Mage::getSingleton('core/resource')->getConnection('core_write');     
try {
	$connection->query($sql);
	die('deleted module in core_resource!');
} catch (Exception $e){
	echo $e->getMessage();
}*/

/*$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		
$write->query("DELETE FROM cron_schedule WHERE job_code = 'simpleexport_export'
");*/

$read = Mage::getSingleton('core/resource')->getConnection('core_read');
$sql = "SELECT `main_table`.*, `schedule_table`.`to_date`, `schedule_table`.`from_date` FROM `magify_bannermanager_banner` AS `main_table` INNER JOIN `magify_bannermanager_banner_schedule` AS `schedule_table` ON main_table.id = schedule_table.banner_id AND schedule_table.type_id = 1 HAVING (schedule_table.from_date <= '2012-07-24' AND schedule_table.to_date >= '2012-07-24')";
$cnt = $read->query($sql);

echo "<pre>";
foreach($cnt as $item ) {
	var_dump($item);
}
echo "</pre>";

/*if (($handle = fopen("area.csv", "r")) !== FALSE) {
	while (($data = fgetcsv($handle, 1024, ",")) !== FALSE) {
		$pincode = $data[0];
		$area = $data[1];
		
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$write->query("UPDATE `fashionara_pincode` SET area = '$area' WHERE pincode='$pincode'");
	}
}*/


/*$model = Mage::getModel('trackorder/trackorder')->getCollection()
				->addFieldToFilter('order_id', '68YgVKpQV2RMada0')->getFirstItem();

$data['order_id'] 	= '100000411';
$data['sale_time'] 	= NOW();
$data['sale'] 		= '1';

echo $model->getId();

$model1 = Mage::getModel('trackorder/trackorder');
$model1->setData($data)
	->setId($model->getId());
$model1->save();*/
/*$modelUpdate = Mage::getModel('trackorder/trackorder')->load(67);
$ipaddress = $modelUpdate->getData('ipaddress');
$source = $modelUpdate->getData('source');*/

/*$collection = Mage::getModel('trackorder/trackorder')->getCollection();
				

foreach( $collection as $item) {
					
	$item->load();
					
	$model = Mage::getModel('trackorder/trackorder');
 
	$model->setId($item->getId())
			->delete();
}*/
/*	$expiration_days = time() + 60 * 60 * 24 * 2;
	$expiration_date = date("Y-m-d 23:59:59", $expiration_days);
	$today = date("Y-m-d 23:59:59", time());
	
$collection = Mage::getModel('mcoupon/mcoupon')->getCollection()->getSelect()
					->from(array('main_table' => 'fashionara_mcoupon'), array('coupons' => "GROUP_CONCAT(main_table.coupon_code SEPARATOR ',')"))
					->joinInner( array('cust'=>'customer_entity'), "main_table.customer_id = cust.entity_id AND main_table.customer_id IS NOT NULL", array() )
					->joinInner( array('coupon'=>'salesrule_coupon'), "main_table.coupon_code = coupon.code AND coupon.is_primary IS NULL AND coupon.times_used = 0 AND coupon.expiration_date <= '$expiration_date' AND coupon.expiration_date > '$today'", array())
					;
var_dump($collection->assemble());
foreach($collection as $item) {
	echo $item['coupon_code'];
	echo "<bR>";
}*/


/*$html = "<table cellpadding='0' cellspacing='0' border='1'>";
$html .= "<tr height='26px'><td>Coupon Codes</td><td>Usage Limit</td><td>Usage Per Customer</td><td>Expiration Date</td></tr>";
$cnt = 0;
foreach( $collection as $item ) {
	$html .= "<tr height='25px'>";
	$html .= "<td>".$item->getCouponCode()."</td>";
	$html .= "<td>".$item->getUsageLimit()."</td>";
	$html .= "<td>".$item->getUsagePerCustomer()."</td>";
	$html .= "<td>".$item->getExpirationDate()."</td>";
	$html .= "</tr>";
	$cnt++;
}
$html .= "</table>";*/

//Mage::getModel('expiredcoupon/expiredcoupon')->sendExpiredCoupons();
//echo NOW();
?>