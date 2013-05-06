<?php
class Magify_Page_Model_Observer
{
	public function processPreDispatch(Varien_Event_Observer $observer)
	{
		$page = Mage::helper('core/url')->getCurrentUrl() ;
		$base = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
		
		$current_page = str_replace($base,'',$page);
		
		if($current_page == 'credit-application' || $current_page == 'preapprove'){
	
			$action = $observer->getEvent()->getControllerAction();
		
			// Check to see if $action is a Product controller
			if ($action instanceof Mage_Catalog_ProductController)
			{
				$request = $action->getRequest();
				$cache = Mage::app()->getCacheInstance();
		
				// Tell Magento to 'ban' the use of FPC for this request
				$cache->banUse('full_page');
			}
		}
		
	}
}