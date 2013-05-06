<?php
class Magify_BannerManager_Block_Adminhtml_Bannermanager_Edit_Tab_Locations_Custom_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('magify_bannermanager_locations_custom_grid');
        $this->setDefaultSort('key');
        $this->setUseAjax(true);
        if($this->_getBanner()->getData('locations/custom'))
        {
            $this->setDefaultFilter(array('in_custom' => 1));
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
    protected function _getBanner()
    {
        return Mage::registry('magify_bannermanager_current_banner');
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
        if ($column->getId() == 'in_custom')
        {
            $customIds = $this->getSelectedCustom();
            if(empty($customIds))
            {
                $customIds = 0;
            }
            if($column->getFilter()->getValue())
            {
                $this->getCollection()->addFieldToFilter('id', array('in' => $customIds));
            }
            else
            {
                if($customIds)
                {
                    $this->getCollection()->addFieldToFilter('id', array('nin' => $customIds));
                }
            }
        }
        else
        {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    } 
     
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('magify_bannermanager/custom')->getCollection();
        $this->setCollection($collection);
    	return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
		$this->addColumn('in_custom', array(
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'name'      => 'in_custom',
            'values'    => $this->getSelectedCustom(),
		    'align'     => 'center',
            'index'     => 'id',
        ));

        $this->addColumn('customgrid[id]', array(
            'header'    => Mage::helper('magify_bannermanager')->__('ID'),
            'align'     => 'left',
        	'width'		=> '20px',
            'index'     => 'id',
        ));

        $this->addColumn('customgrid[title]', array(
            'header'    => Mage::helper('magify_bannermanager')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));
        
        $this->addColumn('customgrid[key]', array(
            'header'    => Mage::helper('magify_bannermanager')->__('Key'),
            'align'     => 'left',
            'index'     => 'key',
        ));
    	
        $this->addColumn('customgrid[module]', array(
            'header'    => Mage::helper('magify_bannermanager')->__('Module'),
            'align'     => 'left',
            'index'     => 'module',
        ));
            	
        $this->addColumn('customgrid[controller]', array(
            'header'    => Mage::helper('magify_bannermanager')->__('Controller'),
            'align'     => 'left',
            'index'     => 'controller',
        ));
        
        $this->addColumn('customgrid[view]', array(
            'header'    => Mage::helper('magify_bannermanager')->__('View'),
            'align'     => 'left',
            'index'     => 'view',
        ));
        
        return parent::_prepareColumns();
    }
    
    public function getGridUrl()
    {
        return $this->getUrl('*/*/locationscustomgrid', array('_current'=>true));
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
     * Retrieve Selected Location Cms Pages
     *
     * @return array
     */
    public function getSelectedCustom()
    {
    	$customs = $this->_getBanner()->getData('locations/custom');
    	
    	if($customs)
    	{
        	$customs = array_keys($customs);
    	}
        return $customs;
    }

    public function getRowUrl($row)
    {
        return '';
    }     
}