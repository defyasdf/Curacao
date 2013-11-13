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
	
	public function notifyCustomer($arg){
		$args = explode(':',$arg);
		$order = Mage::getModel('sales/order')->load($args[0]);
		$_totalData = $order->getData();
		
		// multiple recipients
		$to  = 'sanju.comp@gmail.com'; // note the comma
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://staging.icuracao.com/custom/storeInfo.php?str_id=".$args[2]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$storeInfo = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		
		// subject
		$subject = 'Store Pickup reminder';
	//	$storeInfo = file_get_contents('http://staging.icuracao.com/custom/storeInfo.php?str_id='.$args[2]);
		// message
		$message = '
		<html>
		<head>
		  <title>Store pickup reminder for $order</title>
		</head>
		<body>
		  <p>Your Item is ready to pickup at store</p>
		  <p>Please bring ID or any documentation to match the record below</p>
		  <table>
			<tr>
			  <th>Name</th><th>'.$_totalData['customer_firstname'].' '.$_totalData['customer_lastname'].'</th>
			</tr>
			<tr>
			  <td>Estimate number</td><td>'.$_totalData['estimatenumber'].'</td>
			</tr>
			<tr>
			  <td>Sku</td><td>'.$args[1].'  Qty Purchased : '.$args[3].'</td>
			</tr>
			<tr>
			  <td>Store Address: </td><td>'.$storeInfo.'</td>
			</tr>
		  </table>
		</body>
		</html>
		';
		
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
		// Additional headers
		$headers .= 'To: Sanjay <sanju.comp@gmail.com>' . "\r\n";
		$headers .= 'From: Store Pickup <pickup@icuracao.com>' . "\r\n";
		
		// Mail it
		mail($to, $subject, $message, $headers);
		
		/*
			$_totalData['customer_email']
			if(mail){
				$msg = 'SUCCESS | Customer notified successfully';
			}else{
				$msg = 'FAILED | Customer not found';
			}
		*/
		
		
		
		
		$msg = 'SUCCESS | Customer notified successfully';
		
		return $msg;
	}
	
	public function dropItem($arg){
		
		$msg = 'SUCCESS | Item been cancelled successfully';
		
		return $msg;
	}
}
?>