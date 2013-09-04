<?php
class Magestore_Storepickup_Block_Allstores extends Mage_Core_Block_Template
{

	public function __construct()
	{
		parent::__construct();
	}
		
	public function _prepareLayout()
    {
		parent::_prepareLayout();
        
        $data = $this->getAllStores();
        
		$pager = $this->getLayout()->createBlock('page/html_pager', 'storepickup.allstores.pager')->setCollection($data);
        $this->setChild('pager', $pager);
        $data->load();
        return $this;
	}
	
	public function getAllStores()
	{
		if(!$this->hasData('allstores')) {
			$collection = Mage::getModel('storepickup/store')->getCollection()
							->addFieldToFilter('status',1);
							//->setOrder('store_name','ASC');
			
			if($this->getRequest()->getParam('viewstore')) {
				$collection = $collection->addFieldToFilter('store_id',$this->getRequest()->getParam('viewstore'));
			}
			if ($this->getRequest()->getParam('country'))
			{				
				$country = $this->getRequest()->getParam('country');
				$collection = $collection->addFieldToFilter('country',array('like'=>'%'.$country.'%'));
			}
			if ($this->getRequest()->getParam('state'))
			{				
				$state = $this->getRequest()->getParam('state');
				$state = trim($state);
				$collection = $collection->addFieldToFilter('state',array('like'=>'%'.$state.'%'));
			}
			if ($this->getRequest()->getParam('city'))
			{				
				$city = $this->getRequest()->getParam('city');
				$city = trim($city);
				$collection = $collection->addFieldToFilter('city',array('like'=>'%'.$city.'%'));
			}
			if ($this->getRequest()->getParam('name'))
			{				
				$name = $this->getRequest()->getParam('name');
				$name = trim($name);
				$collection = $collection->addFieldToFilter('store_name',array('like'=>'%'.$name.'%'));
			}
			$this->setData('allstores', $collection);
		}
		return $this->getData('allstores');
	}
	public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
	
	public function getCoordinates($store)
	{
		//$store = $this->getStore();
		$address['street'] = $store->getSuburb();
		$address['street'] = '';
		$address['city'] = $store->getCity();
		$address['region'] = $store->getRegion();
		$address['zipcode'] = $store->getZipcode();
		$address['country'] = $store->getCountryName();
		
		$coordinates = Mage::getModel('storepickup/gmap')
							->getCoordinates($address);
		if(! $coordinates)
		{	
			$coordinates['lat'] = '0.000';
			$coordinates['lng'] = '0.000';			
		}

		return $coordinates;
	}
	
	public function getGKey()
	{
		if(!$this->hasData('g_key'))
		{
			$this->setData('g_key',Mage::getModel('storepickup/gmap')->getGKey());
		}
		
		return $this->getData('g_key');	
	}

	public function getCountryName($country)
	{
		$country = Mage::getResourceModel('directory/country_collection')
						->addFieldToFilter('country_id',$country)
						->getFirstItem();
		return $country->getName();				
	}
	
	public function getSearchConfiguration()
	{
		$storeId = Mage::app()->getStore()->getId();
		$searchconfig = array();
		$searchconfig['country'] = Mage::getStoreConfig("carriers/storepickup/search_country",$storeId);
		$searchconfig['state'] = Mage::getStoreConfig("carriers/storepickup/search_state",$storeId);
		$searchconfig['city'] = Mage::getStoreConfig("carriers/storepickup/search_city",$storeId);
		$searchconfig['name'] = Mage::getStoreConfig("carriers/storepickup/search_name",$storeId);
		return $searchconfig;
	}
	
	public function getFormData($field=null){
            $formData = Mage::getSingleton('core/session')->getPickupFormData();
            if($field)
                return isset($formData[$field]) ? $formData[$field] : null;
            return $formData;
    }
	
	// (06-02-2013)
	public function getDefaultZoom(){
		$storeId = Mage::app()->getStore()->getId();
		return Mage::getStoreConfig("carriers/storepickup/storezoom", $storeId);
	}
}