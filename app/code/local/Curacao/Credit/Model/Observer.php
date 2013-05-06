<?php
class Curacao_Credit_Model_Observer
{
	
	public function hookToOrderSaveEvent($observer)
	{
		
                $orderEvent = $observer->getEvent();
                $order = $orderEvent->getOrder();

		$curorder = Mage::getModel("sales/order")->load($order->getId());
		
		$_curacao_data = null;
		$_curacao_data_file = null;
		$_curacao_data_radio = null;
		$_curacao_data_number = Mage::getSingleton('core/session')->setCuracaoCreditCustomerNumber();
		$_curacao_data_discount = Mage::getSingleton('core/session')->getCuracaoCreditCustomerDiscount();
		
		//Save values to order obcject
		$curorder ->setData("curacaocustomernumber", $_curacao_data_number);
		$curorder ->setData("curacaocustomerdiscount", $_curacao_data_discount );
		$curorder ->save();


                $model = Mage::getModel('salesrule/rule')
		        ->getCollection()
		        ->addFieldToFilter('name', array('eq'=>sprintf('AUTO_GENERATION CUSTOMER_%s - Curacao Credit', Mage::getSingleton('customer/session')->getCustomerId())))
		        ->getFirstItem();
		 
		$model->delete();
		return $this;
	}

}