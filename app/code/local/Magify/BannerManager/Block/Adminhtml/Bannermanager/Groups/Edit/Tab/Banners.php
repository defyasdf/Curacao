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
class Magify_BannerManager_Block_Adminhtml_Bannermanager_Groups_Edit_Tab_Banners extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('magify_bannermanager_group_children');
        $this->setDefaultSort('id');
        $this->setUseAjax(true);
        if($this->_getBannerGroup()->getData('child_banners'))
        {
            $this->setDefaultFilter(array('in_banners' => 1));
        }
        if($this->isReadonly())
        {
            $this->setFilterVisibility(false);
        }
    }
       
    /**
     * Retrive currently edited banner model
     *
     * @return Magify_BannerManager_Model_Banner
     */
    protected function _getBannerGroup()
    {
        return Mage::registry('magify_bannermanager_current_banner_group');
    }

    /**
     * Add filter
     *
     * @param object $column
     * @return Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Related
     */
    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in banner flag
        if($column->getId() == 'in_banners')
        {
            $bannerIds = $this->_getSelectedBanners();
            if(empty($bannerIds))
            {
                $bannerIds = 0;
            }
            if($column->getFilter()->getValue())
            {
                $this->getCollection()->addFieldToFilter('id', array('in' => $bannerIds));
            }
            else
            {
                if($bannerIds)
                {
                    $this->getCollection()->addFieldToFilter('id', array('nin' => $bannerIds));
                }
            }
        }
        else
        {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    /**
     * Prepare collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('magify_bannermanager/banner')
        	->getCollection()
        	->addStats();

    	if(in_array(Mage::getStoreConfig('magify_bannermanager/settings/mode'), array(1, 2)))
    	{
    		$collection->addFilter('position', $this->_getBannerGroup()->getPagePosition());    		
    	}
    	else
    	{
    		$collection->addFilter('position', array('in' => array('', $this->_getBannerGroup()->getPagePosition())), 'public');    		
    	}
        	
        if($this->isReadonly())
        {
            $bannerIds = $this->_getSelectedBanners();
            if (empty($bannerIds))
            {
                $bannerIds = array(0);
            }
            $collection->addFieldToFilter('id', array('in' => $bannerIds));
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return false;
    }

    
    protected function _prepareColumns()
    {
        if (!$this->isReadonly()) {
            $this->addColumn('in_banners', array(
                'header_css_class'  => 'a-center',
                'type'              => 'checkbox',
                'name'              => 'in_banners',
                'values'            => $this->_getSelectedBanners(),
                'align'             => 'center',
                'index'             => 'id'
            ));
        }
    	    	
        $this->addColumn('grid[id]', array(
        	'name'		=> 'grid[id]',
            'header'    => Mage::helper('magify_bannermanager')->__('ID'),
            'width'     => '50px',
            'index'     => 'id',
        ));
        
        $this->addColumn('grid[file]', array(
        	'name'		=> 'grid[file]',
            'header'    => Mage::helper('magify_bannermanager')->__('Image'),
            'index'     => 'file',
        	'renderer'	=> 'magify_bannermanager/adminhtml_bannermanager_grid_renderer_image',
        	'width'		=> '1px',
        ));
                
        $this->addColumn('grid[title]', array(
        	'name'		=> 'grid[title]',
            'header'   	=> Mage::helper('magify_bannermanager')->__('Title'),
            'index'     => 'title',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('grid[banner_store]', array(
        		'name'				=> 'grid[banner_store]',
                'header'    		=> Mage::helper('magify_bannermanager')->__('Store View'),
                'index'    			=> 'banner_store',
                'type'      		=> 'store',
            	'width'     		=> '200px',
            	'store_all'     	=> true,
                'store_view'    	=> true,
                'sortable'      	=> true,
                'filter_condition_callback'
                                	=> array($this, '_filterStoreCondition'),
            ));
        }
                
        $this->addColumn('grid[banner_impression_count]', array(
        	'name'					=> 'grid[banner_impression_count]',
        	'header'    			=> Mage::helper('magify_bannermanager')->__('Impressions'),
        	'index'    				=> 'banner_impression_count',
            'sortable'      		=> false,
            'filter'      			=> false,
        	'width'     			=> '100px',
		));
		
        $this->addColumn('grid[banner_click_count]', array(
        	'name'					=> 'grid[banner_click_count]',
        	'header'    			=> Mage::helper('magify_bannermanager')->__('Clicks'),
        	'index'    				=> 'banner_click_count',
            'sortable'      		=> false,
            'filter'      			=> false,
           	'width'     			=> '100px',
		));
		
        $this->addColumn('grid[status]', array(
        	'name'		=> 'grid[status]',
        	'header'    => Mage::helper('magify_bannermanager')->__('Status'),
            'width'     => '80px',
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                1 => 'Enabled',
                0 => 'Disabled',
            ),
        ));

        $this->addColumn('position', array(
        	'name'				=> 'position',
            'header'            => Mage::helper('catalog')->__('Position'),
            'type'              => 'number',
            'validate_class'    => 'validate-number',
            'index'             => 'position',
            'width'             => 60,
        	//'values'			=> $this->_getSelectedBanners(),
        	'editable'			=> true,
        	'edit_only'			=> false,
        ));
        
    }

    /**
     * Rerieve grid URL
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getData('grid_url')
            ? $this->getData('grid_url')
            : $this->getUrl('*/*/children', array('_current' => true));
    }
    
    /**
     * Rerieve Row URL
     * Set to return nothing to prevent page jumping on selection, URL not required for this grid.
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }
    
    /**
     * Retrieve selected related products
     *
     * @return array
     */
    public function _getSelectedBanners()
    {
        $banners = $this->getChildBanners();
    	if (!is_array($banners)) {
        	$banners = $this->getSelectedBanners();
        	if(is_array($banners))
        	{
        		$banners = array_keys($banners);
        	}
    	}
        return $banners;
    }

    /**
     * Retrieve related products
     *
     * @return array
     */
    public function getSelectedBanners()
    {
        $banners = $this->_getBannerGroup()->getData('child_banners');
        if(is_array($banners))
        {
	        foreach($banners as $id => $position)
	        {
	            $banners[$id] = array('position' => $position);
	        }
        	return $banners;
        }
    }
}
