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
class Magify_BannerManager_Model_Resource_Banner_Group_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{	
    /**
     * Collection resource initialization
     */
    protected function _construct()
    {
        $this->_init('magify_bannermanager/banner_group');
        $this->_map['fields']['id'] 					= 'main_table.id';
        $this->_map['fields']['group_store'] 			= 'group_store_table.store_id';
        $this->_map['fields']['page_filter']   			= 'location_table.entity_id';
        $this->_map['fields']['customer_group']   		= 'customer_group_table.customer_group_id';
        $this->_map['fields']['from_date']   			= 'schedule_group_table.from_date';
        $this->_map['fields']['to_date']   				= 'schedule_group_table.to_date';
        $this->_map['fields']['schedule']   			= 'schedule_group_table.group_id';
        $this->_map['fields']['children']				= 'children_table.group_id';
        
        $this->_pageData = Mage::getSingleton('magify_bannermanager/config')->getPageData();
    }
    
    protected function _afterLoad()
    {
		parent::_afterLoad();
        foreach ($this->_items as $item)
        {
            $item->afterLoad();
        }
        return parent::_afterLoad();
    }
    
   	/**
     * Join banner store relation table if there is store filter
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('group_store')) {
            $this->getSelect()->join(
                array('group_store_table' => $this->getTable('magify_bannermanager/banner_group_store')),
                'main_table.id = group_store_table.group_id',
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
                array('customer_group_table' => $this->getTable('magify_bannermanager/banner_group_customergroup')),
                'main_table.id = customer_group_table.group_id',
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
                array('location_table' => $this->getTable('magify_bannermanager/banner_group_location')),
                'main_table.id = location_table.group_id AND location_table.entity_type_id = ' . $this->_pageData['type'],
                array()
            )->group('main_table.id');
                                    
            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        
        if($this->getFilter('children'))
        {
            $this->getSelect()->join(
                array('children_table' => $this->getTable('magify_bannermanager/banner_group_children')),
                'main_table.id = children_table.group_id',
                array()
            )->group('main_table.id');


            Mage::getResourceHelper('core')->addGroupConcatColumn($this->getSelect(), 'children_ids', 'children_table.banner_id');
            
            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        
        if($this->getFilter('schedule_to'))
        {
            $this->getSelect()->join(
                array('schedule_group_table' => $this->getTable('magify_bannermanager/banner_group_schedule')),
                'main_table.id = schedule_group_table.group_id',
                array()
            )->group('main_table.id');

            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        
        if($this->getFilter('schedule'))
        {
            $this->getSelect()->join(
                array('schedule_group_table' => $this->getTable('magify_bannermanager/banner_group_schedule')),
                "main_table.id = schedule_group_table.group_id",
                array()
            )
            ->where("schedule_group_table.from_date <= '2011-10-10' AND schedule_group_table.to_date >= '2011-10-10'")
            ->group('main_table.id');
        	
            
            //Mage::getResourceHelper('core')->addGroupConcatColumn($this->getSelect(), 'from_date', 'schedule_group_table.from_date');
                        
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
     * @return Magify_BannerManager_Model_Resource_Banner_Group_Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if($store instanceof Mage_Core_Model_Store)
		{
            $store = array($store->getId());
        }
        $this->addFilter('group_store', array('in' => ($withAdmin ? array(0, $store) : $store)), 'public');
        return $this;
    }
    
    public function addStats()
    {
        
        $select = $this->getConnection()->select()
        	->from(array('bgi' => $this->getTable('magify_bannermanager/banner_group_impression')), 'COUNT(bgi.id)')
            ->where('bgi.group_id=main_table.id');
        $impressions  = new Zend_Db_Expr(sprintf('(%s)', $select->assemble()));

        $this->getSelect()->columns(array(
        	'group_impression_count'  => $impressions
        ));
                        
        return $this;
    }
    
    public function addPageFilter()
    {
    	//echo $this->_pageId;
    	$this->addFilter('page_filter', array('in' => $this->_pageData['id']), 'public');
    	return $this;
	}
	
	public function addScheduleFilter()
	{
		
            $this->getSelect()->join(
                array('schedule_group_table' => $this->getTable('magify_bannermanager/banner_group_schedule')),
                "main_table.id = schedule_group_table.group_id AND schedule_group_table.type_id = 1",
                array('to_date' => 'schedule_group_table.to_date', 'from_date' => 'schedule_group_table.from_date')
            )
            
            ->having("schedule_group_table.from_date <= '" . date('Y-m-d', time()) . "' AND schedule_group_table.to_date >= '" . date('Y-m-d', time()) . "'")->group('main_table.id');
            
        /*
        $select = $this->getConnection()->select()
        	->from(array('bgs' => $this->getTable('magify_bannermanager/banner_group_schedule')), '*')
            ->where("bgs.group_id=main_table.id");
        $impressions  = new Zend_Db_Expr(sprintf('(%s)', $select->assemble()));

        $this->getSelect()->columns($impressions);
		*/	

		
        //$this->addFilter('schedule', array('gt' => 0), 'public'); //array('lteq ' => date('Y-m-d', time()))
		//$this->addFilter('schedule', array('lteq' => date('Y-m-d', time())), 'public');
				
		//echo $this->getSelect();die();
		
		
		//$this->addFilter('to_date', array('gteq' => date('Y-m-d', time())));
		//echo $this->getSelect();
		return $this;
	}
	
	public function addChildFilter()
	{
        /*
    	$select = $this->getConnection()->select()
			->from(array('bgc' => $this->getTable('magify_bannermanager/banner_group_children')), 'COUNT(bgc.group_id)')
			->where('bgc.group_id = main_table.id');
			
        $children = new Zend_Db_Expr(sprintf('(%s)', $select->assemble()));
        
        $this->getSelect()->columns(array(
           'children' => $children
       	));
		*/

        $this->addFilter('children', array('gt' => 0), 'public');
        //echo $this->getSelect();
		return $this;
	}
	
    public function addCustomerGroupsFilter()
    {
    	$id = Mage::getSingleton('customer/session')->getCustomerGroupId();
    	$this->addFilter('customer_group', array('in' => $id), 'public');
    	return $this;
	}
        
	public function getChildIds()
	{
		$children = $this->getFirstItem()->getData('children_ids');
		$children = explode(',', $children);
		return array_filter($children, 'strlen');
	}
        
	public function getParams()
	{
		return $this->getFirstItem()->getData('params');
	}
	
	public function getGroup()
	{
		return $this->getFirstItem();
	}
}