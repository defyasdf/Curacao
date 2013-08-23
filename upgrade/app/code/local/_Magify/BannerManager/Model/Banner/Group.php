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
class Magify_BannerManager_Model_Banner_Group extends Magify_BannerManager_Model_Config
{
	
    /**
     * Define main table
     *
     */
    protected function _construct()
    {
        $this->_init('magify_bannermanager/banner_group');
        parent::_construct();
    }

    public function saveLocations($data, $oldData = null)
    {
    	$newData = array();
    	if($categories = $this->getData('locations/categories'))
    	{
    		$newData['categories'] = array_filter(array_unique(explode(',', $categories)), 'strlen');
    	}
    	elseif(isset($oldData['locations']['categories']))
    	{
    		$newData['categories'] = $oldData['locations']['categories'];
    	}
    	
    	if(isset($data['locations']['products']) && $products = $this->getData('locations/products'))
    	{    		
    		$newData['products'] = Mage::helper('adminhtml/js')->decodeGridSerializedInput($products);
    	}
    	elseif(isset($oldData['locations']['products']))
    	{
    		$newData['products'] = $oldData['locations']['products'];
    	}

    	if(isset($data['locations']['pages']) && $pages = $this->getData('locations/pages'))
    	{
    		$newData['pages'] = Mage::helper('adminhtml/js')->decodeGridSerializedInput($pages);
   		}
    	elseif(isset($oldData['locations']['pages']))
    	{
    		$newData['pages'] = $oldData['locations']['pages'];
    	}
    	
    	if(isset($data['locations']['custom']) && $custom = $this->getData('locations/custom'))
    	{
    		$newData['custom'] = Mage::helper('adminhtml/js')->decodeGridSerializedInput($custom);
   		}
    	elseif(isset($oldData['locations']['custom']))
    	{
    		$newData['custom'] = $oldData['locations']['custom'];
    	}
    	
    	$this->setData('locations', $newData);
    	
    	return $this;
    }    
}