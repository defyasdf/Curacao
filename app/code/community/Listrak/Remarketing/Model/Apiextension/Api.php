<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Apiextension_Api extends Mage_Api_Model_Resource_Abstract {
	
	public function products($storeId = 1, $perPage = 50, $page = 1) {
		$collection = Mage::getModel('catalog/product')->getCollection()
            ->addStoreFilter($storeId)
            ->addAttributeToSelect('*')
			->addFieldToFilter('type_id', array('neq'=>'configurable'))
			->setPageSize($perPage)
			->setCurPage($page);
		
		$result = array();
		$productInfo = array('entity_id' => '', 'sku' => '', 'name' => '', 'price' => '', 
			'special_price' => '', 'special_from_date' => '', 'special_to_date' => '', 
			'cost' => '', 'description' => '', 'short_description' => '', 'weight' => '', 
			'url_key' => '', 'url_path' => '', 'image' => '', 'small_image' => '', 'thumbnail' => '');

        foreach ($collection as $product) {
        	$item = array_intersect_key($product->toArray(), $productInfo);
            $result[] = $item;
        }

        return $result;
	}
	
	public function subscribers($storeId = 1, $startDate = NULL, $perPage = 50, $page = 1) {
		if ($startDate === NULL || !strtotime($startDate)) {
			$this->_fault('incorrect_date');
		}
		
		$result = array();
		
		$collection = Mage::getModel("listrak/apiextension")->getResource()->subscribers($storeId, $startDate, $perPage, $page);
		
		foreach($collection as $item) {
			$result[] = $item;
		}
		
		return $result;
	}
	
	public function subscribersPurge($endDate = NULL) {
		if ($endDate === NULL || !strtotime($endDate)) {
			$this->_fault('incorrect_date');
		}
		
		$collection = Mage::getModel("listrak/subscriberupdate")->getCollection()
			->addFieldToFilter('updated_at', array('lt' => $endDate));
			
		$count = $collection->count();
		
		foreach($collection as $c) {
			$c->delete();
		}
		
		return $count;
	}
	
	public function customers($storeId = 1, $websiteId = 1, $perPage = 50, $page = 1) {
		$collection = Mage::getModel('customer/customer')->getCollection()
			->addFieldToFilter('store_id',$storeId)
            ->addAttributeToSelect('*')
            ->setPageSize($perPage)
            ->setCurPage($page);
		
		$results = array();
		$fields = array('entity_id' => '', 'firstname' => '', 'lastname' => '', 'email' => '', 'website_id' => '', 'store_id' => '');
		
		foreach($collection as $customer) {
			$item = $customer->toArray();
			$results[] = array_intersect_key($item, $fields);
		}
		
		return $results;
	}
	
	public function orderStatus($storeId = 1, $startDate = NULL, $endDate = NULL, $perPage = 50, $page = 1, $filters = NULL) {
		$collection = Mage::getModel("sales/order")->getCollection()
			->addFieldToFilter('store_id', $storeId)
            ->addAttributeToSelect('increment_id')
			->addAttributeToSelect('updated_at')
			->addAttributeToSelect('status')
            ->addFieldToFilter('updated_at',array('from'=>$startDate, 'to'=>$endDate))
			->addFieldToFilter('status', array('neq'=>'pending'))
            ->setPageSize($perPage)->setCurPage($page)
			->setOrder('updated_at', 'ASC');
			
		if (is_array($filters)) {
            try {
                foreach ($filters as $field => $value) {
                    if (isset($this->_attributesMap['order'][$field])) {
                        $field = $this->_attributesMap['order'][$field];
                    }

                    $collection->addFieldToFilter($field, $value);
                }
            } catch (Mage_Core_Exception $e) {
                $this->_fault('filters_invalid', $e->getMessage());
            }
        }
		
		$results = array();
		
		foreach($collection as $collectionItem) {
			$results[] = $collectionItem;
		}
		
		return $results;
	}
	
	public function orders($storeId = 1, $startDate = NULL, $endDate = NULL, $perPage = 50, $page = 1) {
		if ($startDate === NULL || !strtotime($startDate)) {
			$this->_fault('incorrect_date');
		}
		
		if ($endDate === NULL || !strtotime($endDate)) {
			$this->_fault('incorrect_date');
		}
		
		$collection = Mage::getModel("sales/order")->getCollection()
            ->addAttributeToSelect('increment_id')
            ->addFieldToFilter('created_at',array('from'=>$startDate, 'to'=>$endDate))
            ->setPageSize($perPage)->setCurPage($page)
			->addFieldToFilter('store_id', $storeId)
			->setOrder('created_at', 'ASC');
		
		
		$apiModel = Mage::getModel("sales/order_api");
		
		$results = array();
		
		$info_fields = array('created_at'=>'', 'status'=>'', 'customer_firstname'=>'', 'customer_lastname'=>'',
			'customer_email'=>'', 'order_id'=>'', 'subtotal'=>'', 'tax_amount'=>'', 'grand_total'=>'',
			'shipping_amount'=>'', 'billing_firstname'=>'');
			
		$address_fields = array('firstname' => '', 'lastname' => '', 'company' => '', 'address1' => '', 
			'address2' => '', 'city' => '', 'region' => '', 'postcode' => '', 'country' => '');
		
		foreach($collection as $collectionItem) {
			$res = array();
			$item = $apiModel->info($collectionItem->getIncrementId());
			$res["info"] = array_intersect_key($item, $info_fields);
			$res["info"]["order_id"] = $collectionItem->getIncrementId();
			$res["shipping_address"] = array_intersect_key($item["shipping_address"], $address_fields);
			$res["billing_address"] = array_intersect_key($item["billing_address"], $address_fields);
			$res["product"] = array();
			foreach($item["items"] as $orderItem) {
				if($orderItem["product_type"] != "configurable")
					$res["product"][] = $orderItem;
			}
			$res["session"] = Mage::getModel("listrak/session")->load($item["quote_id"], 'quote_id');
			$results[] = $res;
		}
			
    	return $results;
	}

	public function info() {
		$result = array();
		$result["ini"] = array();
		
		$subModel = Mage::getModel("newsletter/subscriber");
		$orderModel = Mage::getModel("sales/order");
		$productModel = Mage::getModel('catalog/product');
		
		$result["classes"] = get_class($subModel) .','. get_class($orderModel) .','. get_class($orderModel->getCollection()) .','.
			  get_class($productModel) .','. get_class($productModel->getCollection());
		
		$ra = Mage::getSingleton('core/resource')->getConnection('core_read');
        $numSessions = $ra->fetchRow("select count(*) as c from " . Mage::getModel("listrak/session")->getResource()->getTable("listrak/session"));
		$numSubUpdates = $ra->fetchRow("select count(*) as c from " . Mage::getModel("listrak/subscriberupdate")->getResource()->getTable("listrak/subscriber_update"));
		$numClicks = $ra->fetchRow("select count(*) as c from " . Mage::getModel("listrak/click")->getResource()->getTable("listrak/click"));
		
		$result["counts"] = $numSessions['c'] .','. $numSubUpdates['c'] .','. $numClicks['c']; 
		
		$result["modules"] = array();
		$modules = (array)Mage::getConfig()->getNode('modules')->children();
		
		foreach($modules as $key => $value) {
			$valueArray = $value->asCanonicalArray();
			$result["modules"][] = "name=$key, version=" . $valueArray["version"] .", isActive=" . $valueArray["active"];
		}
		
		$ini = array("session.gc_maxlifetime", "session.cookie_lifetime", "session.gc_divisor", "session.gc_probability");
		
		foreach($ini as $iniParam) {
			$result["ini"][] = "$iniParam=" . ini_get($iniParam);
		}
		
		return $result;
	}
}
	