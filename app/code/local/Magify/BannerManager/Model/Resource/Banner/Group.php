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
class Magify_BannerManager_Model_Resource_Banner_Group extends Mage_Core_Model_Resource_Db_Abstract
{
	protected $_locationMap = array();
	protected $_locationIdMap = array();
		
    /**
     * Define main table
     *
     */
    protected function _construct()
    {
        $this->_init('magify_bannermanager/banner_group', 'id');
        $this->_locationMap = array('categories' => 1, 'pages' => 2, 'products' => 3, 'custom' => 4);
        $this->_locationIdMap = array(1 => 'categories', 2 => 'pages', 3 => 'products', 4 => 'custom');
    }
    
    /**
     * Process banner group data before saving
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Magify_BannerManager_Model_Resource_Banner_Group
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {

        // modify create / update dates
        if ($object->isObjectNew() && !$object->hasCreatedDate()) {
            $object->setCreatedDate(Mage::getSingleton('core/date')->gmtDate());
        }

        $object->setUpdateDate(Mage::getSingleton('core/date')->gmtDate());

        //Only save values different from defaults. Compares from default values defined in config.xml
        if($params = $object->getData('params'))
        {
	        $availableOptions = Mage::getModel('magify_bannermanager/banner')->getFrontends($params['frontend']);
	        if(isset($params['params']))
	        {
		        foreach($params['params'] as $key => $option)
		        {
		        	if(isset($availableOptions['params'][$key]['default']))
		        	{
			        	//echo $key . ' = ' . $option . ' = ' . $availableOptions['params'][$key]['default'] . '<br />'; 
			        	if($option == $availableOptions['params'][$key]['default'])
			        	{
			        		unset($params['params'][$key]);
			        	}
		        	}
		        }
	        }
			$object->setData('params', serialize($params));
		}
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
        $newStores = (array)$object->getGroupStore();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
       	}
        $table  = $this->getTable('magify_bannermanager/banner_group_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = array(
                'group_id = ?'     	=> (int) $object->getId(),
                'store_id IN (?)' 	=> $delete
            );

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();

            foreach ($insert as $storeId) {
                $data[] = array(
                    'group_id'  	=> (int) $object->getId(),
                    'store_id' 		=> (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        
        $condition = $this->_getWriteAdapter()->quoteInto('group_id = ?', $object->getId());

        //Save Schedule
        if($object->getData('group_schedule'))
        {
            $this->_getWriteAdapter()->delete($this->getTable('magify_bannermanager/banner_group_schedule'), $condition);
	        foreach((array) $object->getData('group_schedule') as $schedule)
			{
				$entities 					= array();
				$entities['group_id'] 		= $object->getId();
				$entities['type_id'] 		= 1;
				$entities['from_date'] 		= $schedule['from_date'];
				$entities['to_date'] 		= $schedule['to_date'];
				
				$this->_getWriteAdapter()->insert($this->getTable('magify_bannermanager/banner_group_schedule'), $entities);
	        }
        }
        
        //Save Locations
        if($object->getData('locations'))
        {
            $this->_getWriteAdapter()->delete($this->getTable('magify_bannermanager/banner_group_location'), $condition);
	        foreach((array) $object->getData('locations') as $locationType => $entities)
			{
				foreach($entities as $entity_id)
				{
					$entities 					= array();
					$entities['group_id'] 		= $object->getId();
					$entities['entity_type_id'] = $this->_locationMap[$locationType];
					$entities['entity_id'] 		= $entity_id;
					
					$this->_getWriteAdapter()->insert($this->getTable('magify_bannermanager/banner_group_location'), $entities);
				}
	        }
        }
                
        //Save Assocations
        if($object->getData('child_banners'))
        {
            $this->_getWriteAdapter()->delete($this->getTable('magify_bannermanager/banner_group_children'), $condition);
	        foreach((array)$object->getData('child_banners') as $banner_id => $position)
			{
					$child 				= array();
					$child['group_id'] 	= $object->getId();
					$child['banner_id']	= $banner_id;
					$child['position'] 	= $position['position'];
					//print_r($child);die(0);
					$this->_getWriteAdapter()->insert($this->getTable('magify_bannermanager/banner_group_children'), $child);
	        }
        }
        
        //Save Customer Groups
        if($object->getData('customer_group'))
        {
            $this->_getWriteAdapter()->delete($this->getTable('magify_bannermanager/banner_group_customergroup'), $condition);
	        foreach((array) $object->getData('customer_group') as $group)
			{
				$entities 					= array();
				$entities['group_id'] 		= $object->getId();
				$entities['customer_group_id'] 		= $group;
				
				$this->_getWriteAdapter()->insert($this->getTable('magify_bannermanager/banner_group_customergroup'), $entities);
	        }
        }
                
        return parent::_afterSave($object);
    }    
    
    /**
     * Perform operations after object load
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Magify_BannerManager_Model_Resource_Banner_Group
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if($object->getId())
        {
        	//Decode Serialized Params
        	$object->setData('params', unserialize($object->getData('params')));
        	
			//Join Banner Group Stores
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_group_store'))
                ->where('group_id = ?', $object->getId());

            if($data = $this->_getReadAdapter()->fetchAll($select))
			{
                $storesArray = array();
                foreach ($data as $row) {
                    $storesArray[] = $row['store_id'];
                }
                $object->setData('group_store', $storesArray);
            }
            
            //Join Banner Group Schedule
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_group_schedule'), array('from_date', 'to_date'))
                ->where('group_id = ?', $object->getId());

            if($data = $this->_getReadAdapter()->fetchAll($select))
			{
                $object->setData('group_schedule', $data);
            }
            
            //Join Banner Group Impressions
            /*
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_group_impression'))
                ->where('group_id = ?', $object->getId());

            if($data = $this->_getReadAdapter()->fetchAll($select))
			{
                $object->setData('group_impression_count', count($data));
            }
            */
            //Join Banner Group Children
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_group_children'))
                ->where('group_id = ?', $object->getId());

            if($children = $this->_getReadAdapter()->fetchAll($select))
			{
				$childrenData = array();
				foreach($children as $child) {
					$childrenData[$child['banner_id']] = $child['position'];
				}
				$object->setData('child_banners', $childrenData);
            }
            
            //Join Banner Group Locations
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_group_location'), array('entity_type_id','entity_id'))
                ->where('group_id = ?', $object->getId());

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
			
            //Join Banner Group Customer Groups
            $select = $this->_getReadAdapter()->select()
                ->from($this->getTable('magify_bannermanager/banner_group_customergroup'), array('customer_group_id'))
                ->where('group_id = ?', $object->getId());

            if($data = $this->_getReadAdapter()->fetchAll($select))
			{
                $groups = array();
                foreach ($data as $row) {
                    $groups[] = $row['customer_group_id'];
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
    public function lookupStoreIds($groupId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('magify_bannermanager/banner_group_store'), 'store_id')
            ->where('group_id = ?',(int)$groupId);

        return $adapter->fetchCol($select);
    }
}