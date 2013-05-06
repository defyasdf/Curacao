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
class Magify_BannerManager_Model_Banner extends Magify_BannerManager_Model_Config
{
    
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('magify_bannermanager/banner');
        parent::_construct();
    }

    public function loadHistory()
    {
    	return Mage::getResourceSingleton('magify_bannermanager/banner')->loadHistory($this);
    }
    
    public function saveLocations($data, $oldData = null)
    {
    	$newData = array();
    	
    	if($categories = $this->getData('locations/categories')) {
    		$newData['categories'] = array_filter(array_unique(explode(',', $categories)), 'strlen');
    	} elseif(isset($oldData['locations']['categories'])) {
    		$newData['categories'] = $oldData['locations']['categories'];
    	}
    	
    	if(isset($data['locations']['products']) && $products = $this->getData('locations/products')) {    		
    		$newData['products'] = Mage::helper('adminhtml/js')->decodeGridSerializedInput($products);
    	} elseif(isset($oldData['locations']['products'])) {
    		$newData['products'] = $oldData['locations']['products'];
    	}

    	if(isset($data['locations']['pages']) && $pages = $this->getData('locations/pages')) {
    		$newData['pages'] = Mage::helper('adminhtml/js')->decodeGridSerializedInput($pages);
   		} elseif(isset($oldData['locations']['pages'])) {
    		$newData['pages'] = $oldData['locations']['pages'];
    	}
    	
    	if(isset($data['locations']['custom']) && $custom = $this->getData('locations/custom')) {
    		$newData['custom'] = Mage::helper('adminhtml/js')->decodeGridSerializedInput($custom);
   		} elseif(isset($oldData['locations']['custom'])) {
    		$newData['custom'] = $oldData['locations']['custom'];
    	}
    	
    	$this->setData('locations', $newData);
    	
    	return $this;
    }
    
    public function saveResources()
    {
		$file = $this->getData('file');
	    if(isset($file['delete']) && $file['delete'] == 1) {
			$this->setData('file', '');
			return $this;
	    }
	    
    	$path = Mage::getBaseDir('media') . DS . str_replace('/', DS, self::BANNER_FILE_PATH) . DS;
    	
    	if(isset($_FILES['file']['name']) && (file_exists($_FILES['file']['tmp_name']))) {
    		$name = $_FILES['file']['tmp_name'];
    		    		
  			try {
				$uploader = new Varien_File_Uploader('file');
				
				$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
 
			    $uploader->setAllowRenameFiles(true)
			    	->setFilesDispersion(false);
			   			               
			    $uploader->save($path, $_FILES['file']['name']);
				
			    $this->setData('file', $uploader->getUploadedFileName());

  			}
			catch(Mage_Core_Exception $e)
			{
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addException($e,
                    Mage::helper('magify_bannermanager')->__('An error occurred while saving the banner image.'));
            }
		} elseif(is_array($this->getData('file'))) {
			$this->unsetData('file');
		}
		
		$file = $this->getData('file');
	    
	    if(isset($file['delete']) && $file['delete'] == 1) {
			$this->setData('file', '');die(0);
	    }
	
		$content = $this->getData('content');
		
		if( isset($content) ){
			$this->setData('content', $content);
		}
	
	    return $this;
    }
    
    /**
     * 
     */
    protected function _beforeSave()
    {
        $this->cleanCache();
    	parent::_beforeSave();
    }
    
    /**
     * 
     */
    protected function _beforeDelete()
    {
        $this->cleanCache();
    	parent::_beforeDelete();
    }
    
    /**
     * Clear cache related with banner id
     *
     * @return Magify_BannerManager_Model_Banner
     */
    public function cleanCache()
    {
        Mage::app()->cleanCache(self::CACHE_TAG.'_'.$this->getId());
        return $this;
    }
        
    protected function _invalidateCache()
    {
        $types = Mage::getConfig()->getNode(self::XML_NODE_RELATED_CACHE);
        if ($types) {
            $types = $types->asArray();
            Mage::app()->getCacheInstance()->invalidateType(array_keys($types));
        }
        return $this;
    }
}