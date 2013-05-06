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
class Magify_BannerManager_Model_Resource_Banner extends Mage_Core_Model_Resource_Db_Abstract
{
	protected $_locationMap = array();
	protected $_locationIdMap = array();
	
    /**
     * Define main table
     *
     */
    protected function _construct()
    {
        $this->_init('magify_bannermanager/banner', 'id');
        
        $this->_locationMap = array('categories' => 1, 'pages' => 2, 'products' => 3, 'custom' => 4);
        $this->_locationIdMap = array(1 => 'categories', 2 => 'pages', 3 => 'products', 4 => 'custom');
    }
    
    public function loadHistory(Magify_BannerManager_Model_Banner $banner)
    {
        $adapter = $this->getReadConnection();
        $bind    = array(':banner_id' => $banner->getId());
        $select  = $adapter->select()
            ->from($this->getTable('magify_bannermanager/banner_history'))
            ->where('banner_id = :banner_id');
        return $adapter->fetchAll($select, $bind);
    }
    
    public function addPageFilter()
    {
        $adapter = $this->getReadConnection();
        $bind    = array(':banner_id' => $banner->getId());
        $select  = $adapter->select()
            ->from($this->getTable('magify_bannermanager/banner'))
            ->where('banner_id = :banner_id');
            
        return $this;
    }
        
    /**
     * Process banner data before saving
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Magify_BannerManager_Model_Resource_Banner
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {

        // modify create / update dates
        if ($object->isObjectNew() && !$object->hasCreatedDate()) {
            $object->setCreatedDate(Mage::getSingleton('core/date')->gmtDate());
        }

        $object->setUpdateDate(Mage::getSingleton('core/date')->gmtDate());

        return parent::_beforeSave($object);
    }
    
    /**
     * Assign banner to store views
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Magify_BannerManager_Model_Resource_Banner
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
    	
    	//Save Store Ids
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array) $object->getBannerStore();
        if (empty($newStores)) {
            $newStores = (array) $object->getStoreId();
        }
        $table  = $this->getTable('magify_bannermanager/banner_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = array(
                'banner_id = ?'		=> (int) $object->getId(),
                'store_id IN (?)'	=> $delete
            );

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();

            foreach ($insert as $storeId) {
                $data[] = array(
                    'banner_id'		=> (int) $object->getId(),
                    'store_id' 		=> (int) $storeId
                );
            }

            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        
        $condition = $this->_getWriteAdapter()->quoteInto('banner_id = ?', $object->getId());

        //Save Assocations
        if($object->getData('banner_associated_entities'))
        {
            $this->_getWriteAdapter()->delete($this->getTable('magify_bannermanager/banner_associated_entities'), $condition);
	        foreach((array) $object->getData('banner_associated_entities') as $entity_type_id => $entities)
			{
				foreach($entities as $entity_id => $position)
				{
					$entities 					= array();
					$entities['banner_id'] 		= $object->getId();
					$entities['entity_type_id'] = $entity_type_id;
					$entities['entity_id'] 		= $entity_id;
					$entities['position'] 		= $position;
					
					$this->_getWriteAdapter()->insert($this->getTable('magify_bannermanager/banner_associated_entities'), $entities);
				}
	        }
        }
        
        //Save Locations
        if($object->getData('locations'))
        {
            $this->_getWriteAdapter()->delete($this->getTable('magify_bannermanager/banner_location'), $condition);
	        foreach((array) $object->getData('locations') as $locationType => $entities)
			{
				foreach($entities as $entity_id)
				{
					$entities 					= array();
					$entities['banner_id'] 		= $object->getId();
					$entities['entity_type_id'] = $this->_locationMap[$locationType];
					$entities['entity_id'] 		= $entity_id;
					
					$this->_getWriteAdapter()->insert($this->getTable('magify_bannermanager/banner_location'), $entities);
				}
	        }
        }
        
        //Save Schedule
        if($object->getData('banner_schedule'))
        {
            $this->_getWriteAdapter()->delete($this->getTable('magify_bannermanager/banner_schedule'), $condition);
	        foreach((array) $object->getData('banner_schedule') as $schedule)
			{
				$entities 					= array();
				$entities['banner_id'] 		= $object->getId();
				$entities['type_id'] 		= 1;
				$entities['from_date'] 		= $schedule['from_date'];
				$entities['to_date'] 		= $schedule['to_date'];
				
				$this->_getWriteAdapter()->insert($this->getTable('magify_bannermanager/banner_schedule'), $entities);
	        }
        }
        
        //Save Customer Groups
        if($object->getData('customer_group'))
        {
            $this->_getWriteAdapter()->delete($this->getTable('magify_bannermanager/banner_customergroup'), $condition);
	        foreach((array) $object->getData('customer_group') as $group)
			{
				$entities 					= array();
				$entities['banner_id'] 		= $object->getId();
				$entities['group_id'] 		= $group;
				
				$this->_getWriteAdapter()->insert($this->getTable('magify_bannermanager/banner_customergroup'), $entities);
	        }
        }
        
        return parent::_afterSave($object);
    }
        
        
    /**
     * Perform operations after object load
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Magify_BannerManager_Model_Resource_Banner
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId())
        {
        	
			//Join Banner Stores
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_store'))
                ->where('banner_id = ?', $object->getId());

            if($data = $this->_getReadAdapter()->fetchAll($select))
			{
                $stores = array();
                foreach ($data as $row)
                {
                    $stores[] = $row['store_id'];
                }
                $object->setData('banner_store', $stores);
            }
            
            //Join Banner Clicks
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_click'))
                ->where('banner_id = ?', $object->getId());

            if($data = $this->_getReadAdapter()->fetchAll($select))
			{
                $object->setData('banner_click_count', count($data));
            }
            
            
            //Join Banner Impressions
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_impression'))
                ->where('banner_id = ?', $object->getId());

            if($data = $this->_getReadAdapter()->fetchAll($select))
			{
                $object->setData('banner_impression_count', count($data));
            }
            
            //Join Banner Schedule
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_schedule'), array('from_date', 'to_date'))
                ->where('banner_id = ?', $object->getId());

            if($data = $this->_getReadAdapter()->fetchAll($select))
			{
                $object->setData('banner_schedule', $data);
            }
            
            //Join Banner Entities
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_associated_entities'), array('entity_id','entity_type_id','position'))
                ->where('banner_id = ?', $object->getId());

            if($data = $this->_getReadAdapter()->fetchAll($select))
			{
				$entities = array();
				foreach($data as $entity)
				{
					$entities[$entity['entity_type_id']][$entity['entity_id']] = $entity['position'];
				}
                $object->setData('banner_associated_entities_types', array_keys($entities));
                $object->setData('banner_associated_entities', $entities);
			}
            
            //Join Banner Locations
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_location'), array('entity_type_id','entity_id'))
                ->where('banner_id = ?', $object->getId());

            if($data = $this->_getReadAdapter()->fetchAll($select))
			{
				$entities = array();
				foreach($data as $entity)
				{
					$entities[$this->_locationIdMap[$entity['entity_type_id']]][] = $entity['entity_id'];
				}
				$object->setData('locations', $entities);
			}
			
			if(isset($entities['custom']))
			{
	            $select = $this->_getReadAdapter()->select()
	                ->from($this->getTable('magify_bannermanager/custom'), array('id','key'))
	                ->where('id IN (?)', $entities['custom']);
	                
	            if($custom = $this->_getReadAdapter()->fetchAll($select))
				{
					$data = array();
					foreach($custom as $c)
					{		
						$data[$c['id']] = $c['key'];
					}
					$entities['custom'] = $data;
					$object->setData('locations', $entities);
				}												
			}
			
            //Join Banner Customer Groups
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_customergroup'), array('group_id'))
                ->where('banner_id = ?', $object->getId());

            if($data = $this->_getReadAdapter()->fetchAll($select))
			{
                $groups = array();
                foreach ($data as $row) {
                    $groups[] = $row['group_id'];
                }
                $object->setData('customer_group', implode(',', $groups));
            }
        }

        return parent::_afterLoad($object);
    }
    
    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($bannerId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('magify_bannermanager/banner_store'), 'store_id')
            ->where('banner_id = ?', (int) $bannerId);

        return $adapter->fetchCol($select);
    }
        
}