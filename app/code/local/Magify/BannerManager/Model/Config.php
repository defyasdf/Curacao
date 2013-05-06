<?php
/**
 * Magify Commerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magify.com so we can send you a copy immediately.
 *
 * @category    Magify
 * @package     Magify_BannerManager
 * @copyright   Copyright (c) 2012 Magify Commerce (http://www.magify.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Magify_BannerManager_Model_Config extends Mage_Core_Model_Abstract
{
    /**
     * Related Cache Types
     * @var String
     */
	const XML_NODE_RELATED_CACHE = 'global/magify_bannermanager/related_cache_types';
    
    /**
     * Banner Cache Tag
     * @var String
     */
    const CACHE_TAG             = 'magify_bannermanager';
    
    /**
     * Associated Entity Type Id's
     * @var int
     */
    const PRODUCT_ENTITY 		= 1;
    const CATEGORY_ENTITY 		= 2;
    const CMS_PAGE_ENTITY		= 3;
    const CMS_BLOCK_ENTITY		= 4;
    
    /**
     * Banner URL Identifiers
     * @var String
     */
    const STATIC_LINK				= 'static';
    const CMS_PAGE_LINK				= 'cmspage';
    const CATALOG_PRODUCT_LINK		= 'catalogproduct';
    const CATALOG_CATEGORY_LINK		= 'catalogcategory';
    const CONTACTS_FORM_LINK		= 'contacts';
   	const CUSTOMER_LOGIN_LINK		= 'customerlogin';
    const CUSTOMER_ACCOUNT_LINK		= 'customeraccount';
    const CHECKOUT_CART_LINK		= 'checkoutcart';
    const CHECKOUT_ONEPAGE_LINK		= 'checkoutonepage';
    const WISHLIST_LINK				= 'wishlist';
    
    /**
     * Available Resource type's
     * @var int
     */
    const STATIC_RESOURCE			= 	'content';
    const FILE_RESOURCE				= 	'file';
    protected $resource_ids			= 	array(
	    									'content' 	=> 1,
	    									'file' 		=> 2,
    								  	);
    
    /**
     * Banner Statuses
     * @var bool
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
		
    
    /**
     * Banner Operation Modes
     * @var bool
     */
    const MODE_AUTO_AND_MANUAL 	= 1;
    const MODE_AUTO 			= 2;
    const MODE_MANUAL 			= 3;
    
    const LOCATION_MAP_CATEGORIES 	= 1;
    const LOCATION_MAP_PAGES 		= 2;
    const LOCATION_MAP_PRODUCTS 	= 3;
    const LOCATION_MAP_CUSTOM 		= 4;
    
    
    
    /**
     * Banner File Path
     * @var string
     */
    const BANNER_FILE_PATH = 'magify_resources/bannermanager/banner_images';
    
    protected $_frontends;
    protected $_positions;
    protected $_custom;
    protected $_location_map;
    protected $_pageData;
    
    
    /**
     * Load config to cache.
     */
    protected function _construct()
    {                
        $this->loadFrontends();
        $this->loadPositions();
        $this->loadCustomLocations();
        $this->loadPageData();
    }
    
    public function getLocation($value, $type = 'id')
    {
    	if(is_null($this->_location_map))
    	{
			$this->_location_map = array(
				'categories' 	=> self::LOCATION_MAP_CATEGORIES,
				'pages' 		=> self::LOCATION_MAP_PAGES,
				'products' 		=> self::LOCATION_MAP_PRODUCTS,
				'custom' 		=> self::LOCATION_MAP_CUSTOM,
			);
    	}
    	
    	if($value)
    	{
	    	if($type == 'id')
	    	{
	    		foreach($this->_location_map as $name => $id)
	    		{
	    			if($value == $name) return $id;
	    		}
	    	}	
	    		
	    	if($type == 'name')
	    	{
	    		foreach($this->_location_map as $name => $id)
	    		{
	    			if($value == $id) return $name;
	    		}
	    	}
    	}
    		
    	return $this->_location_map;
    }
        
    /**
     * @return array
     */
    public function getAvailableStatuses()
    {
        $statuses = new Varien_Object(array(
            self::STATUS_ENABLED => Mage::helper('magify_bannermanager')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('magify_bannermanager')->__('Disabled'),
        ));

        Mage::dispatchEvent('magify_bannermanager_get_available_banner_statuses', array('statuses' => $statuses));

        return $statuses->getData();
    }
    
    
    public function loadPageData()
    {
    	$request 	= Mage::app()->getFrontController()->getRequest();
		$module 	= $request->getModuleName();
		$controller = $request->getControllerName();
		$view 		= $request->getActionName();
		
		switch($module)
		{		
			case 'cms':
				$this->_pageData = array('type' => self::LOCATION_MAP_PAGES, 'id' => Mage::getBlockSingleton('cms/page')->getPage()->getId());
				break;
				
			case 'catalog':
				if($controller == 'category')
				{
					$this->_pageData = array('type' => self::LOCATION_MAP_CATEGORIES, 'id' => Mage::registry('current_category')->getId());
					break;
				}
				elseif($controller == 'product')
				{
					$this->_pageData = array('type' => self::LOCATION_MAP_PRODUCTS, 'id' => Mage::registry('current_product')->getId());
					break;
				}
				
			default:
				$this->_pageData = array('type' => self::LOCATION_MAP_CUSTOM, 'id' => $this->getCustomPageId($module, $controller, $view));
			break;
		}
	}
	
	public function getPageData()
	{
		return $this->_pageData;
	}
	
	public function getCustomPageId($module, $controller, $view)
	{
		$custom = Mage::getModel('magify_bannermanager/custom')->getCollection();
		
		$path = $module;
		if(!is_null($controller))
		{
			$path .= '_' . $controller;
		}
		if(!is_null($view))
		{
			$path .= '_' . $view;
		}
		
		foreach($custom as $c)
		{
			if($path == $c['key'])
			{
				return $c['id'];
			}
		}
		return 0;
	}
	        
    
    
    /**
     * @return array
     */
    public function loadFrontends()
    {
    	if(is_null($this->_frontends))
    	{
        	$cacheKey = 'VORTEX_BANNERMANAGER_FRONTENDS_' . Mage::app()->getStore()->getCode();   
    	 	if (Mage::app()->useCache('config') && $cache = Mage::app()->loadCache($cacheKey))
    	 	{
            	$this->_frontends = unserialize($cache);
        	}
        	else
        	{
    			$this->_frontends = Mage::getConfig()->getNode('global/magify_bannermanager/frontends')->asArray();
	        	if (Mage::app()->useCache('config'))
	        	{
	                Mage::app()->saveCache(serialize($this->_frontends), $cacheKey, array('config'));
	            }    			
        	}
    	}
    	return $this->_frontends;
   	}
   	
   	public function getFrontends($frontend = null)
   	{
   		if($frontend)
   		{
   			if(isset($this->_frontends[$frontend]))
   			{
   				return $this->_frontends[$frontend];
   			}
   		}
    	return $this->_frontends;
   	}
   	
   	public function getAvailableFrontends()
   	{
   		$available = array();
   		foreach($this->_frontends as $id => $frontend)
   		{
   			$available[$id] = $frontend['title'];
   		}
   		return $available;
   	}
   	   	
   	public function loadPositions()
   	{
    	if(is_null($this->_positions))
    	{
        	$cacheKey = 'VORTEX_BANNERMANAGER_POSITIONS_' . Mage::app()->getStore()->getCode();   
    	 	if (Mage::app()->useCache('config') && $cache = Mage::app()->loadCache($cacheKey))
    	 	{
            	$this->_positions = unserialize($cache);
        	}
        	else
        	{
    			$this->_positions = Mage::getConfig()->getNode('global/magify_bannermanager/positions')->asArray();
	        	if (Mage::app()->useCache('config'))
	        	{
	                Mage::app()->saveCache(serialize($this->_positions), $cacheKey, array('config'));
	            }    			
        	}
    	}

	    return $this->_positions;
   	}
   	
   	public function getPositions()
   	{
    	return $this->_positions;
   	}
   	
   	public function getAvailablePositions()
   	{
   		$available = array();
   		foreach($this->getPositions() as $id => $position)
   		{
   			$available[$id] = $position;
   		}
   		return $available;
   	}
   	   	
   	public function loadCustomLocations()
   	{
    	if(is_null($this->_custom))
    	{
        	$cacheKey = 'VORTEX_BANNERMANAGER_BANNER_CUSTOM_' . Mage::app()->getStore()->getCode();   
    	 	if (Mage::app()->useCache('config') && $cache = Mage::app()->loadCache($cacheKey))
    	 	{
            	$this->_custom = unserialize($cache);
        	}
        	else
        	{
    			$this->_custom = Mage::getConfig()->getNode('global/magify_bannermanager/custom')->asArray();
	        	if (Mage::app()->useCache('config'))
	        	{
	                Mage::app()->saveCache(serialize($this->_custom), $cacheKey, array('config'));
	            }    			
        	}
    	}
   		//print_r($this->_custom);die(0);
    	    	
	    return $this->_custom;
   	}
   	
   	public function getCustomLocations()
   	{
    	return $this->_custom;
   	}
   	
   	public function getCustomLocationsCollection()
   	{
   		return new Varien_Object($this->_custom);
   	}
   	
    public function getResourceTypes()
    {
    	$options = array();
    	
        $options[self::FILE_RESOURCE] = Mage::helper('magify_bannermanager')->__('Image Upload');
        $options[self::STATIC_RESOURCE] = Mage::helper('magify_bannermanager')->__('Custom HTML');
                
        Mage::dispatchEvent('magify_bannermanager_resource_type_options', array('options' => $options));

        return $options;
    }
        
    /**
     * Clear cache related with banner id
     *
     * @return Magify_BannerManager_Model_Banner
     */
    public function cleanCache()
    {
        Mage::app()->cleanCache(self::CACHE_TAG.'_'.$this->getId());
        return $this;
    }
        
    protected function _invalidateCache()
    {
        $types = Mage::getConfig()->getNode(self::XML_NODE_RELATED_CACHE);
        if ($types) {
            $types = $types->asArray();
            Mage::app()->getCacheInstance()->invalidateType(array_keys($types));
        }
        return $this;
    }
}