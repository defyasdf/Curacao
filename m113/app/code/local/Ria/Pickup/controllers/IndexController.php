<?php
class Ria_Pickup_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction(){
		echo $this->getLayout()
        ->createBlock('pickup/pickup')
        ->setTemplate('pickup/storepickup.phtml')
        ->toHtml();
	}
	public function setsessionvalAction(){
		$params = $this->getRequest()->getParams();
		Mage::getSingleton('core/session')->setStoreId($params['store_id']);
		Mage::getSingleton('core/session')->setProductSku($params['sku']);
		$block = $this->getLayout()->createBlock('pickup/pickup');
		$storeinfo = $block->loadStore($params['store_id'])->getData();
		$session = Mage::getSingleton("core/session",  array("name"=>"frontend"));
		
		$session->setData($params['sku'],$storeinfo['store_name']);
		$session->setData($params['sku'].'_storeId',$params['store_id']);
		echo $storeinfo['store_name'];
	}
	
}
