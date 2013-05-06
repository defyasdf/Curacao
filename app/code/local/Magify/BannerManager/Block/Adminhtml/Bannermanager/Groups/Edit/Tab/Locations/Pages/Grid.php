<?php
class Magify_BannerManager_Block_Adminhtml_Bannermanager_Groups_Edit_Tab_Locations_Pages_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('magify_bannermanager_groups_locations_pages_grid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        if($this->_getBannerGroup()->getData('locations/pages'))
        {
            $this->setDefaultFilter(array('in_pages' => 1));
        }
        if ($this->isReadonly())
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
        // Set custom filter for in product flag
        if($column->getId() == 'in_pages')
        {
            $pageIds = $this->_getSelectedPages();
            if (empty($pageIds))
            {
                $pageIds = 0;
            }
            if($column->getFilter()->getValue()) 
            {
                $this->getCollection()->addFieldToFilter('page_id', array('in' => $pageIds));
            }
            else
            {
                if($pageIds)
                {
                    $this->getCollection()->addFieldToFilter('page_id', array('nin' => $pageIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    } 
        
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('cms/page')->getCollection();
        /* @var $collection Mage_Cms_Model_Mysql4_Page_Collection */
        $collection->setFirstStoreFlag(true);
        $this->setCollection($collection);
    	return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
    	
		$this->addColumn('in_pages', array(
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'name'      => 'in_pages',
            'values'    => $this->_getSelectedPages(),
		    'align'     => 'center',
            'index'     => 'page_id',
        ));
    	
        $this->addColumn('grid[title]', array(
            'header'    => Mage::helper('cms')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));

        $this->addColumn('grid[identifier]', array(
            'header'    => Mage::helper('cms')->__('URL Key'),
            'align'     => 'left',
            'index'     => 'identifier'
        ));



        $this->addColumn('grid[root_template]', array(
            'header'    => Mage::helper('cms')->__('Layout'),
            'index'     => 'root_template',
            'type'      => 'options',
            'options'   => Mage::getSingleton('page/source_layout')->getOptions(),
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('grid[store_id]', array(
                'header'        => Mage::helper('cms')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                                => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('grid[is_active]', array(
            'header'    => Mage::helper('cms')->__('Status'),
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => Mage::getSingleton('cms/page')->getAvailableStatuses()
        ));

        $this->addColumn('grid[creation_time]', array(
            'header'    => Mage::helper('cms')->__('Date Created'),
            'index'     => 'creation_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('grid[update_time]', array(
            'header'    => Mage::helper('cms')->__('Last Modified'),
            'index'     => 'update_time',
            'type'      => 'datetime',
        ));
        
        return parent::_prepareColumns();
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('*/*/locationspagesgrid', array('_current'=>true));
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
        
    /**
     * Retrieve selected related products
     *
     * @return array
     */
    protected function _getSelectedPages()
    {
        $pages = $this->getPages();
    	if(!is_array($pages))
    	{
            $pages = $this->getSelectedPages();
        }
        return $pages;
    }    
    
    /**
     * Retrieve Selected Location Cms Pages
     *
     * @return array
     */
    public function getSelectedPages()
    {
        return $this->_getBannerGroup()->getData('locations/pages');
    }
    public function getRowUrl($row)
    {
        return '';
    }
            
}