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
class Magify_BannerManager_Block_Factory extends Mage_Core_Block_Template
{
	protected $_debug = false;
	protected $_debugData;
	protected $_position;
	
	protected $_collection;
	protected $_bannerCollection;
	protected $_groupCollection;

	protected $_count;
	protected $_mode;
	protected $_params;
	protected $_data;
	protected $_frontend;
	protected $_jsParamsArray;
	
	protected $_wrapperstyles = array();
	protected $_styles = array();
	protected $_classes = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->_collection = null;
		$this->_debug = Mage::getStoreConfig('magify_bannermanager/developer/debug');
		$this->_mode = Mage::getStoreConfig('magify_bannermanager/settings/mode');
	}
	
	public function isDebug()
	{
		return $this->_debug;
	}

    /**
    * @return void
    */
    public function setPosition($position)
    {
    	if($this->positionAllowed($position))
    	{
        	$this->_position = $position;
    	}
		
		//Load Banners
		Varien_Profiler::start('Magify_BannerManager_Load_Banner_Collection');
    	$this->getBannerGroup();
		Varien_Profiler::stop('Magify_BannerManager_Load_Banner_Collection');
    }
    
    public function getPosition()
    {
    	return $this->_position;
    }
	    
    public function positionAllowed($position)
    {
    	$allowed = Mage::getModel('magify_bannermanager/banner')->getAvailablePositions();
    	$allowed = array_keys($allowed);
    	
		if($this->isDebug())
		{
			$this->_debugData['allowed_positions'] = implode(', ', $allowed);
		}
    	    	
    	if(in_array($position, $allowed))
    	{
    		return true;
    	}
    	
    	return false;
    }
	
	protected function _getBannerCollection()
	{
		Varien_Profiler::start('Magify_BannerManager_Load_Banner_Model');
				
		if(is_null($this->_bannerCollection))
		{
			$this->_bannerCollection = Mage::getModel('magify_bannermanager/banner')->getCollection()
				->addFilter('status', 1)
				->addStoreFilter(Mage::app()->getStore());

			if(in_array($this->_mode, array(Magify_BannerManager_Model_Config::MODE_AUTO_AND_MANUAL, Magify_BannerManager_Model_Config::MODE_MANUAL)))
			{
				$this->_bannerCollection
						->addScheduleFilter()
						->addCustomerGroupsFilter()
						->addPageFilter();
			}
			
			Mage::dispatchEvent('magify_bannermanager_banner_factory_banner_collection_after_load', array('collection' => $this->_bannerCollection));
		}
		
		Varien_Profiler::stop('Magify_BannerManager_Load_Banner_Model');
				
		return $this->_bannerCollection;
	}
	
	public function _getGroupCollection()
	{
		Varien_Profiler::start('Magify_BannerManager_Load_Banner_Group_Model');
		
		if(is_null($this->_groupCollection))
		{
			$this->_groupCollection = Mage::getModel('magify_bannermanager/banner_group')->getCollection()
				->addFilter('status', 1)
				->addStoreFilter(Mage::app()->getStore())
				->addCustomerGroupsFilter()
				->addScheduleFilter()
				->addPageFilter()
				->addStats()
				->addChildFilter()
				->setOrder('sort', 'desc');
			Mage::dispatchEvent('magify_bannermanager_banner_factory_group_collection_after_load', array('collection' => $this->_groupCollection));
		}
		
		Varien_Profiler::stop('Magify_BannerManager_Load_Banner_Group_Model');
		
		return $this->_groupCollection;
	}
	
    /**
     * @return Magify_BannerManager_Model_Resource_Banner_Collection
     * 
     * Filter banner collection based on page and postion etc...
     */
	public function getBanners()
	{
		return (is_null($this->_position)) ? null : $this->_collection;
	}
	
	public function getBannerGroup()
	{
		/*** AUTO & MANUAL MODE ***/
		if($this->_mode == Magify_BannerManager_Model_Config::MODE_AUTO_AND_MANUAL)
		{
			if($this->isDebug()) $this->_debugData['collection']['type'] = 'Auto/Manual Group Collection';
			
			///Try get manual groups first
			$this->_collection 	= $this->_getGroupCollection()->addFilter('page_position', $this->_position);
			$children 			= $this->_collection->getChildIds();
						
			///Get group children
			if(count($children))
			{
				$this->_params = $this->_collection->getParams();
				if($this->isDebug()) $this->_debugData['collection']['params'] = $this->_params;
				$this->_collection = $this->_getBannerCollection()->addIdFilter($children)->addFilter('position', $this->_position);
			}
			
			///Fallback to auto groups
			if(!$this->_collection->count())
			{
				$this->_collection = $this->_getBannerCollection()->addFilter('position', $this->_position);
				//unset($this->_params);
			}
		}
		/*** MANUAL MODE ***/
		elseif($this->_mode == Magify_BannerManager_Model_Config::MODE_MANUAL)
		{
			if($this->isDebug()) $this->_debugData['collection']['type'] = 'Manual Group Collection';
						
			$this->_collection 	= $this->_getGroupCollection()->addFilter('page_position', $this->_position);
			$this->_params 	= $this->_collection->getParams();
			$this->_data	= $this->_collection->getData();
			$children 		= $this->_collection->getChildIds();

			if($this->isDebug()) 
			{
				$this->_debugData['collection']['data'] 	= $this->_data;
				$this->_debugData['collection']['params'] 	= $this->_params;
			}
						
			if($children)
			{
				$this->_collection = $this->_getBannerCollection()
										->addIdFilter($children)
										->addFilter('position', $this->_position);
			}
			
		}
		/*** AUTO MODE ***/
		elseif($this->_mode == Magify_BannerManager_Model_Config::MODE_AUTO)
		{
			if($this->isDebug()) $this->_debugData['collection']['type'] = 'Auto Group Collection';
						
			$this->_collection 	= $this->_getBannerCollection()
									->addFilter('position', $this->_position);
		}

		$this->_count = $this->_collection->count();
		if($this->_count)
		{
			if($this->isDebug())
			{
				$this->_debugData['current_store'] = Mage::app()->getStore()->getId();
				$this->_debugData['collection']['size'] = $this->_count;
				$this->_debugData['collection']['select'] = (string) $this->_collection->getSelectSql(); //@todo get this to work.
			}
			
			$template = $this->getTemplateName($this->_position);
			$this->setTemplate($template);
			
			if($this->isDebug())
			{
				$this->_debugData['position'] = $this->_position;
				$this->_debugData['template'] = $this->getTemplate();
			}
			
			$this->loadStyles();
			
			$this->prepareImages();
			return $this->_collection->load();
			return ($this->_collection) ? $this->_collection->load() : null;
			
		}
		return null;
	}
	
	public function prepareImages()
	{
		Varien_Profiler::start('Magify_BannerManager_Load_Banner_PrePareImages');
				
		if(!is_null($this->_collection))
		{
			$_helper = Mage::helper('magify_bannermanager');
			foreach($this->_collection as $banner)
			{
				$background = ($banner->getBackgroundColor()) ? $banner->getBackgroundColor() : 'ffffff';
				$banner->setResizeFile($_helper->getBannerImage($banner->getFile(), $this->_params['resize'], $background, $this->_styles['image']['width'], $this->_styles['image']['height']));
			}
		}
		
		Varien_Profiler::stop('Magify_BannerManager_Load_Banner_PrePareImages');
		return;
		return $this->_collection;
	}

	public function getParams()
	{
		return $this->_params;
	}

	public function getSize()
	{
		return $this->_count;
	}
	
    /**
     * @return void
     * 
     * Calculates the largest width and height of all banner image in the collection.
     */
	public function loadStyles()
	{
		$path = Mage::getBaseDir('media') . DS . str_replace('/', DS, Magify_BannerManager_Model_Banner::BANNER_FILE_PATH) . DS;
		if(isset($this->_params['resize']))
		{
			$path .= 'cache' . DS;
			if(isset($this->_params['resize']['width'])) $path .= $this->_params['resize']['width'];
			$path .= 'x';
			if(isset($this->_params['resize']['height'])) $path .= $this->_params['resize']['height'];
			$path .= DS;
		}
		$height = 0;
		$width = 0;
		$i=0; foreach($this->_collection as $banner)
		{
			$backgroundHex = (isset($banner['background_color']) && $banner['background_color']) ? $banner['background_color'] : 'ffffff';
			
			if($i++==0 && Mage::getStoreConfig('magify_bannermanager/settings/fluid_background'))
			{
				$this->_styles['wrapper']['background-color'] = '#' . $backgroundHex;
			}
			
			if($colourPath = $this->helper('magify_bannermanager')->hex2RGB($backgroundHex, true, '-'))
			{
				$colourPath = $path . $colourPath . DS;
			}
			
			$file = (file_exists($path . $banner['file'])) ? $path . $banner['file'] : $colourPath . $banner['file'];
			
			$size = getimagesize($file);
			if($size[0] > $width)
			{
				$width = $size[0];
			}
			if($size[1] > $height)
			{
				$height = $size[1];
			}
		}
		
		$this->_styles['banners']['height'] = $height . 'px';
		$this->_styles['banners']['width'] = $width . 'px';
		
		$this->_styles['image']['height'] = $height;
		$this->_styles['image']['width'] = $width;
		
	}
	
    /**
     * @return string
     * 
     * Returns formatted aggregated css styles for the banners container element.
     */
	public function getStyles($type = 'banners')
	{
		if(isset($this->_styles[$type]))
		{
			$styles = 'style="';
			foreach($this->_styles[$type] as $key => $style)
			{
				$styles .= $key . ':' . $style . '; ';
			}
			$styles .= '"';
			return $styles;
		}
		return null;
	}
		
    /**
     * @return string
     * 
     * Load the best template. Configured in Admin -> System -> Configuration -> VORTEX COMMERCE -> Banner Manager -> General Settings.
     */
	public function getTemplateName($position)
	{
		$template = 'default';
		
		$this->_frontend = (isset($this->_params['frontend'])) ? $this->_params['frontend'] : Mage::getStoreConfig('magify_bannermanager/settings/frontend');

		if(!is_null($this->_frontend) && $this->_frontend != 'custom')
		{
			$_frontends = Mage::getModel('magify_bannermanager/banner')->getFrontends();
			if(isset($_frontends[$this->_frontend]['template']))
			{
				$template = $_frontends[$this->_frontend]['template'];
			}
			else
			{
				$template = $this->_frontend;
			}
		}
		elseif($this->_frontend == $custom)
		{
			$custom = Mage::getStoreConfig('magify_bannermanager/settings/customer');
			if(!is_null($custom))
			{
				return trim($custom, '/');
			}
		}
		//Allow for position name template override.
		if(Mage::getStoreConfig('magify_bannermanager/settings/position_template'))
		{
			if(file_exists(Mage::getDesign()->getTemplateFilename('magify/bannermanager/' . $position . '/' . $template . '.phtml')))
			{
				return 'magify/bannermanager/' . $position . '/' . $template . '.phtml';
			}
		}
		return 'magify/bannermanager/' . $template . '.phtml';
	}
	
	public function getJsParams($_param = null)
	{
		Varien_Profiler::start('Magify_BannerManager_Load_Banner::getJsParams');				
		
		$_jsParams = '';
		
		if(is_null($this->_jsParamsArray))
		{
			$_defaults = Mage::getSingleton('magify_bannermanager/config')->getFrontends($this->_frontend);
			$_params = $this->_params['params'];
			
			foreach($_defaults['params'] as $key => $param)
			{
				$value = ((isset($_params[$key])) ? $_params[$key] : $param['default']);
							
				if(isset($param['text']))
				{
					$value = "'" . $value ."'";
				}
				elseif(isset($param['bool']))
				{
					$value = (($value) ? 'true' : 'false');
				}
							
				$this->_jsParamsArray[$key] = $value;
			}
		}
		
		if($_param)
		{
			return $this->_jsParamsArray[$_param];
		}
		
		foreach($this->_jsParamsArray as $key => $value)
		{
			$_jsParams .= $key . ': ' . $value . ", ";
		}
		
		Varien_Profiler::stop('Magify_BannerManager_Load_Banner::getJsParams');
				
		return $_jsParams;
	}
    
    public function getDebugData()
    {
    	echo '<pre>';
    	print_r($this->_debugData);
    	echo '</pre>';
    }
}