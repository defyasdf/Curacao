<?php

	require_once('./_includes/ini_settings.php');
	require_once('./_includes/mage_head.php');

	//$xml=simplexml_load_file("http://morris.morriscostumes.com/wbxml/819677/out/daily_summary_".date('mdY',strtotime('-1 days')).".xml");
	$xml=simplexml_load_file("http://morris.morriscostumes.com/wbxml/819677/out/daily_summary_10212013.xml");
	$xmlsize = sizeof($xml);
	$inventrydata = $xml->OrderDetail;
	foreach($inventrydata as $product){
		$order_morris = get_object_vars($product->Order);
		$order_header = get_object_vars($product->Order->Header);
		$products = get_object_vars($product);
		$skus = array();
		foreach($order_morris['LineItems']->Line as $lines){
			$sku = get_object_vars($lines);
			$skus[] = $sku['Part'];
		}
		if(trim($order_header['po']) == ''){
			//return false;
		}else{
			
			$order = Mage::getModel('sales/order')->loadByIncrementId($order_header['po']);
			//echo $order->getStatus().' : '.$order_header['po'].'<br>';

			//exit;
			if($order->getStatus() != 'canceled' && $order->getStatus() != 'complete'){
				if($order_morris['Status'] == 'Shipped'){
					$box = get_object_vars($order_morris['Boxes']->Box);
						completeShipment($order_header['po'],$box['TrackNum'],$skus,$order_header['Via']);
					echo $order_header['po'].'<br>';
				//	exit;
				}else if($order_morris['Status'] == 'Deleted'){
					if($order->getStatus() != 'canceled'){
						$order->addStatusToHistory('outofstock', 'Cancel Reason From morris : '.$products['CancelReason'], false);
						$order->save();
					}
					echo $order_header['po'].'<br>';
				}
			}
		}
		
	}
	
	
	/**
 * Completes the Shipment, followed by completing the Order life-cycle
 * It is assumed that the Invoice has already been generated
 * and the amount has been captured.
 */
function completeShipment($orderid,$shipmenttracking,$skus,$shipInfo){
    /**
     * It can be an alphanumeric string, but definitely unique.
     */
    $orderIncrementId = $orderid;
    /**
     * Provide the Shipment Tracking Number,
     * which will be sent out by any warehouse to Magento
     */
    $shipmentTrackingNumber = $shipmenttracking;
    /**
     * This can be blank also.
     */
    $customerEmailComments = '';
 
    $order = Mage::getModel('sales/order')
                 ->loadByIncrementId($orderIncrementId);
 
    if (!$order->getId()) {
        Mage::throwException("Order does not exist, for the Shipment process to complete");
    }
 
    if ($order->canShip()) {
        try {
            $shipment = Mage::getModel('sales/service_order', $order)
                            ->prepareShipment(_getItemQtys($order,$skus));
 
            /**
             * Carrier Codes can be like "ups" / "fedex" / "custom",
             * but they need to be active from the System Configuration area.
             * These variables can be provided custom-value, but it is always
             * suggested to use Order values
             */
			if (strpos(strtolower($shipInfo),'fedex') !== false) {
        		$shipmentCarrierCode = 'fedex';
    	        $shipmentCarrierTitle = 'Federal Express';
			}elseif (strpos(strtolower($shipInfo),'dhl') !== false) {
        		$shipmentCarrierCode = 'dhl';
    	        $shipmentCarrierTitle = 'DHL (Deprecated)';
			}elseif (strpos(strtolower($shipInfo),'ups') !== false) {
        		$shipmentCarrierCode = 'ups';
    	        $shipmentCarrierTitle = 'United Parcel Service';
			}else{
				$shipmentCarrierCode = 'custom';
    	        $shipmentCarrierTitle = 'Morris&acute;s rate shopping';
			}
			
            $arrTracking = array(
                'carrier_code' => isset($shipmentCarrierCode) ? $shipmentCarrierCode : $order->getShippingCarrier()->getCarrierCode(),
                'title' => isset($shipmentCarrierTitle) ? $shipmentCarrierTitle : $order->getShippingCarrier()->getConfigData('title'),
                'number' => $shipmentTrackingNumber,
            );
 
            $track = Mage::getModel('sales/order_shipment_track')->addData($arrTracking);
            $shipment->addTrack($track);
 
            // Register Shipment
            $shipment->register();
 			
            // Save the Shipment
            _saveShipment($shipment, $order, $customerEmailComments);
 		
	        // Finally, Save the Order
         // if((int)$order->getItemsCollection()->count() == (int)sizeof($skus)){
		   	 _saveOrder($order);
		  //}
        } catch (Exception $e) {
            throw $e;
        }
    }
}
 
/**
 * Get the Quantities shipped for the Order, based on an item-level
 * This method can also be modified, to have the Partial Shipment functionality in place
 *
 * @param $order Mage_Sales_Model_Order
 * @return array
 */
function _getItemQtys(Mage_Sales_Model_Order $order,$skus){
    $qty = array();
	 
    foreach ($order->getAllItems() as $_eachItem) {
        if(in_array($_eachItem->getSku(),$skus)){
			if ($_eachItem->getParentItemId()) {
				$qty[$_eachItem->getParentItemId()] = $_eachItem->getQtyOrdered();
			} else {
				$qty[$_eachItem->getId()] = $_eachItem->getQtyOrdered();
			}
		}
    }
 
    return $qty;
}
 
/**
 * Saves the Shipment changes in the Order
 *
 * @param $shipment Mage_Sales_Model_Order_Shipment
 * @param $order Mage_Sales_Model_Order
 * @param $customerEmailComments string
 */
 function _saveShipment(Mage_Sales_Model_Order_Shipment $shipment, Mage_Sales_Model_Order $order, $customerEmailComments = ''){
    $shipment->getOrder()->setIsInProcess(true);
    $transactionSave = Mage::getModel('core/resource_transaction')
                           ->addObject($shipment)
                           ->addObject($order)
                           ->save();
 
    /*$emailSentStatus = $shipment->getData('email_sent');
    if (!is_null($customerEmail) && !$emailSentStatus) {
        $shipment->sendEmail(true, $customerEmailComments);
        $shipment->setEmailSent(true);
    }*/
 
    return true;
}
 
/**
 * Saves the Order, to complete the full life-cycle of the Order
 * Order status will now show as Complete
 *
 * @param $order Mage_Sales_Model_Order
 */
function _saveOrder(Mage_Sales_Model_Order $order){
    
	try {
		if(!$order->canInvoice()){
			Mage::throwException(Mage::helper('core')->__('Cannot create an invoice.'));
		}
		$invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
		if (!$invoice->getTotalQty()) {
			Mage::throwException(Mage::helper('core')->__('Cannot create an invoice without products.'));
		}
		//echo 'here';
		//exit;
		$payment_method_code = $order->getPayment()->getMethodInstance()->getCode();
		if($payment_method_code != 'pay'){			
			
			$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
		}else{
		
			$invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_OFFLINE);
		}
		$invoice->register();
		$transactionSave = Mage::getModel('core/resource_transaction')
		->addObject($invoice)
		->addObject($invoice->getOrder());
		$transactionSave->save();
	}catch (Mage_Core_Exception $e) {
	}
//	$shipments = $order->getShipmentsCollection();
	$items = $order->getAllItems();
	$itemOrdered = 0;
	$itemShipped = 0;
	foreach($items as $item){
		$itemOrdered += $item->getQtyOrdered();
		$itemShipped += $item->getQtyShipped();
	}
	if((int)$itemOrdered == (int)$itemShipped){
		$order->setData('state', Mage_Sales_Model_Order::STATE_COMPLETE);
		$order->setData('status', Mage_Sales_Model_Order::STATE_COMPLETE);
	}
 
    $order->save();
 
    return true;
}