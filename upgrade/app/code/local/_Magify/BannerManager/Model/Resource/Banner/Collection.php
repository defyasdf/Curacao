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
class Magify_BannerManager_Model_Resource_Banner_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{		
	
	protected $_pageType = null;
	protected $_pageId = null;
	
    /**
     * Collection resource initialization
     */
    protected function _construct()
    {
        $this->_init('magify_bannermanager/banner');
        $this->_map['fields']['id'] 					= 'main_table.id';
        $this->_map['fields']['banner_store']   		= 'banner_store_table.store_id';
        $this->_map['fields']['page_filter']   			= 'location_table.entity_id';
        $this->_map['fields']['customer_group']   		= 'customer_group_table.group_id';
        $this->_map['fields']['schedule_table']   		= 'schedule_table.banner_id';
        $this->_map['fields']['from_date']   			= 'schedule_table.from_date';
        $this->_map['fields']['to_date']   				= 'schedule_table.to_date';
                
		//$cache = Mage::app()->getCacheInstance();
		//$prefix = 'BANNERS';
		//$this->initCache($cache, $prefix, array(Magify_BannerManager_Model_Banner::CACHE_TAG));
        
		$this->getCurrentPageData();
    }
	
    protected function _afterLoad()
    {
		parent::_afterLoad();
        foreach ($this->_items as $item) {
            $item->afterLoad();
        }
        return parent::_afterLoad();
    }
    
    /**
     * Join banner store relation table if there is store filter
     */
    protected function _renderFiltersBefore()
    {
        if($this->getFilter('banner_store'))
        {
            $this->getSelect()->join(
                array('banner_store_table' => $this->getTable('magify_bannermanager/banner_store')),
                'main_table.id = banner_store_table.banner_id',
                array()
            )->group('main_table.id');

            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        
        if($this->getFilter('customer_group'))
        {
            $this->getSelect()->join(
                array('customer_group_table' => $this->getTable('magify_bannermanager/banner_customergroup')),
                'main_table.id = customer_group_table.banner_id',
                array()//array('customer_group' => 'GROUP_CONCAT(customer_group_table.group_id)')
            )->group('main_table.id');
            
            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        
        if($this->getFilter('page_filter'))
        {
            $this->getSelect()->join(
                array('location_table' => $this->getTable('magify_bannermanager/banner_location')),
                'main_table.id = location_table.banner_id AND location_table.entity_type_id = ' . $this->_pageType,
                array()
            )->group('main_table.id');
                                    
            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        
        if($this->getFilter('banner_click_count'))
        {
            $this->getSelect()->join(
                array('banner_click_table' => $this->getTable('magify_bannermanager/banner_click')),
                'main_table.id = banner_click_table.banner_id',
                array('banner_click_count' => 'COUNT(banner_click_table.banner_id)')
            )->group('main_table.id');

            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }

        return parent::_renderFiltersBefore();
    }
    
    /**
     * Add filter by store
     *
     * @param int|Mage_Core_Model_Store $store
     * @return Magify_BannerManager_Model_Resource_Banner_Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if($store instanceof Mage_Core_Model_Store)
		{
            $store = array($store->getId());
        }
        $this->addFilter('banner_store', array('in' => ($withAdmin ? array(0, $store) : $store)), 'public');
        return $this;
    }
        
    public function addCustomerGroupsFilter()
    {
    	$id = Mage::getSingleton('customer/session')->getCustomerGroupId();
    	$this->addFilter('customer_group', array('in' => $id), 'public');
    	return $this;
	}
        
    public function addPageFilter()
    {
    	$this->addFilter('page_filter', array('in' => $this->_pageId), 'public');
    	return $this;
	}
	
	public function addScheduleFilter()
	{
		$this->getSelect()->join(
			array('schedule_table' => $this->getTable('magify_bannermanager/banner_schedule')),
			"main_table.id = schedule_table.banner_id AND schedule_table.type_id = 1",
			array('to_date' => 'schedule_table.to_date', 'from_date' => 'schedule_table.from_date')
 		)
 		//->group('main_table.id')
		->having("schedule_table.from_date <= '" . date('Y-m-d', time()) . "' AND schedule_table.to_date >= '" . date('Y-m-d', time()) . "'")
		;
		//echo $this->getSelect();die(0);
		return $this;
	}

	/**
     * Add Banner Id Filter
     *
     * @param int $ids|array
     * @return Magify_BannerManager_Model_Resource_Banner_Collection
     */
    public function addIdFilter($ids)
    {
        if (is_array($ids)) {
            if (empty($ids)) {
                $this->addFieldToFilter('id', null);
            } else {
                $this->addFieldToFilter('id', array('in' => $ids));
            }
        } else {
            $this->addFieldToFilter('id', $ids);
        }
        return $this;
    }	
		
    public function addStats()
    {
    	$select = $this->getConnection()->select()
			->from(array('bc' => $this->getTable('magify_bannermanager/banner_click')), 'COUNT(bc.id)')
			->where('bc.banner_id=main_table.id');
        $clicks = new Zend_Db_Expr(sprintf('(%s)', $select->assemble()));
        
        $select = $this->getConnection()->select()
        	->from(array('bi' => $this->getTable('magify_bannermanager/banner_impression')), 'COUNT(bi.id)')
            ->where('bi.banner_id=main_table.id');
        $impressions  = new Zend_Db_Expr(sprintf('(%s)', $select->assemble()));

        $this->getSelect()->columns(array(
        	'banner_impression_count'  => $impressions,
            'banner_click_count' => $clicks
        ));
                        
        return $this;
    }
    
    public function getCurrentPageData()
    {
    	$request 	= Mage::app()->getFrontController()->getRequest();
		$module 	= $request->getModuleName();
		$controller = $request->getControllerName();
		$view 		= $request->getActionName();
		
		switch($module)
		{		
			case 'cms':
				$this->_pageType = 2;
				$this->_pageId = Mage::getBlockSingleton('cms/page')->getPage()->getId();
				break;
				
			case 'catalog':
				if($controller == 'category')
				{
					$this->_pageType = 1;
					$this->_pageId = Mage::registry('current_category')->getId();
					break;
				}
				elseif($controller == 'product')
				{
					$this->_pageType = 1;
					$this->_pageId = Mage::registry('current_product')->getId();
					break;
				}
				
			default:
				$this->_pageType = 4;
				$this->_pageId = $this->getCustomPageId($module, $controller, $view);
			break;
		}
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
}