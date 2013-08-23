<?php
class Curacao_Unlockcustomer_Model_ObjectModel_Api extends Mage_Api_Model_Resource_Abstract
{
    public function unlockCustomer($arg)
    {
		$collection = Mage::getModel('customer/customer')
					  ->getCollection()
					  ->addAttributeToSelect('*')
					  ->addFieldToFilter('curacaocustid',$arg);		
		
		$result = array();
		foreach ($collection as $customer) {
			$result[] = $customer->toArray();
		}
		
		if(sizeof($result)>0){
	
			$cid = $result[0]['entity_id'];
			
			$customer = Mage::getModel('customer/customer')->load($cid);
			
			$customer->lockattempt = '0';
			$customer->save();
			$msg = 'SUCCESS | Customer unlocked successfully';
		}else{
			$msg = 'FAILED | Customer not found';
		}
        return $msg;
    }
	public function replaceSku($arg)
    {
		$skus = explode(':',$arg);	
        
		$product = Mage::getModel('catalog/product');
		$productId = $product->getIdBySku($skus[0]);
		if($productId){
			
			$product->load($productId);
			$product->setSku($skus[1]);
			try {
				$product->save();
				$msg = 'SUCCESS';
			}
			catch (Exception $ex) {
				$msg = $ex->getMessage();
			}
			
		}else{
			$msg = 'FAILED | Product Not Found';
		}
	   
	    return $msg;
    }
}
?>