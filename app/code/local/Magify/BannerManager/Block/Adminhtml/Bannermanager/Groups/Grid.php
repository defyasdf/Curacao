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
class Magify_BannerManager_Block_Adminhtml_Bannermanager_Groups_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('magifybannermanagergroupsgrid');
        // This is the primary key of the database
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('magify_bannermanager/banner_group')->getCollection()->addStats()->load();
        //echo $collection->getSelect();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($column->getFilter()->getValue());
    }
            
    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => Mage::helper('magify_bannermanager')->__('ID'),
            'width'     => '50px',
            'index'     => 'id',
        ));
        
        $this->addColumn('title', array(
            'header'    => Mage::helper('magify_bannermanager')->__('Title'),
            'index'     => 'title',
        ));

        $this->addColumn('page_position', array(
        	'header'    			=> Mage::helper('magify_bannermanager')->__('Position'),
        	'index'    				=> 'page_position',
        	'width'     			=> '160px',
            'type'     				=> 'options',
        	'options'				=> Mage::getModel('magify_bannermanager/banner')->getAvailablePositions(),
		));
                
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('group_store', array(
                'header'    		=> Mage::helper('magify_bannermanager')->__('Store View'),
                'index'    			=> 'group_store',
                'type'      		=> 'store',
            	'width'     		=> '200px',
            	'store_all'     	=> true,
                'store_view'    	=> true,
                'sortable'      	=> true,
                'filter_condition_callback'
                                	=> array($this, '_filterStoreCondition'),
            ));
        }
        
        $this->addColumn('banner_impression_count', array(
        	'header'    			=> Mage::helper('magify_bannermanager')->__('Impressions'),
        	'index'    				=> 'banner_impression_count',
            'sortable'      		=> false,
            'filter'      			=> false,
        	'width'     			=> '100px',
		));
        		
        $this->addColumn('status', array(
            'header'    => Mage::helper('magify_bannermanager')->__('Status'),
            'width'     => '80px',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                1 => 'Enabled',
                0 => 'Disabled',
            ),
        ));
    }
    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    } 
}