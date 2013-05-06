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
class Magify_BannerManager_Adminhtml_MagifybannermanagerController extends Mage_Adminhtml_Controller_Action
{
	protected $_model;
	
	protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('cms/magifybannermanager')
            ->_addBreadcrumb(Mage::helper('magify_bannermanager')->__('Banner Manager'), Mage::helper('magify_bannermanager')->__('Banner Manager'));
        return $this;
    }
    
    protected function _initBanner()
    {
        $id = $this->getRequest()->getParam('id');
		$model = Mage::getModel('magify_bannermanager/banner')->load($id);
		$this->_model = $model;
        Mage::register('magify_bannermanager_current_banner', $model);
	}
        
    public function indexAction()
	{
        $this->_title(Mage::helper('magify_bannermanager')->__('CMS') )
        	->_title(Mage::helper('magify_bannermanager')->__('Banner Manager'));
		$this->_initAction();       
        $this->renderLayout();
    }
    
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('magify_bannermanager/adminhtml_bannermanager_grid')->toHtml()
        );
    }
    
    public function newAction()
    {
        $this->editAction();
    }

    public function editAction()
    {
        $this->_title(Mage::helper('magify_bannermanager')->__('CMS') )
        	->_title(Mage::helper('magify_bannermanager')->__('Banners'))
        	->_title(Mage::helper('magify_bannermanager')->__('Manage Banner'));
        	
        //Get ID and create model
        $id = $this->getRequest()->getParam('id');
		$model = Mage::getModel('magify_bannermanager/banner');
				
        //Initial checking
        if($id)
		{
			$model->load($id);
            if (!$model->getId())
			{
                Mage::getSingleton('adminhtml/session')->addError(
                	Mage::helper('magify_bannermanager')->__('This banner no longer exists.')
                );
                $this->_redirect('*/*/');
                return;
            }
        }
        
        
        $this->_title($model->getId() ? $model->getTitle() : Mage::helper('magify_bannermanager')->__('New Banner'));
        //3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if(!empty($data))
		{
            $model->setData($data);
        }
        
		$path = Mage::getBaseUrl('media') . 'magify_resources/bannermanager/banner_images/';
			
		if($model->getData('file')) $model->setData('file', $path . $model->getData('file'));
        
        //print_r($model->getData());die(0);
		                
        //4. Register model to use later in blocks
        Mage::register('magify_bannermanager_current_banner', $model);

        //5. Build edit form
        $this->_initAction()
          ->_addBreadcrumb(
                $id ? Mage::helper('magify_bannermanager')->__('Edit Banner')
                    : Mage::helper('magify_bannermanager')->__('New Banner'),
                $id ? Mage::helper('magify_bannermanager')->__('Edit Banner')
                    : Mage::helper('magify_bannermanager')->__('New Banner'));
                    
		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
                
       	$version = substr(Mage::getVersion(), 0, 3);
        if (($version=='1.4' || $version=='1.5')) {
        	$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
       	}

        $this->renderLayout();

    }

    public function saveAction()
    {
        //Check if data sent
        if($data = $this->getRequest()->getPost())
		{
            $model = Mage::getModel('magify_bannermanager/banner');
            $oldData = null;
            if($id = $this->getRequest()->getParam('id'))
			{
				$model->load($id);
				$oldData = $model->getData();
            }
                        
            $associated_entities = $this->getRequest()->getPost('associated_entities');
	        if (isset($associated_entities['products']))
	        {
	            $data['banner_associated_entities'][Magify_BannerManager_Model_Banner::PRODUCT_ENTITY] = Mage::helper('adminhtml/js')->decodeGridSerializedInput($associated_entities['products']);
	        }
	        
	        if(isset($data['banner_schedule']))
	        {
	        	$error = false;
	        	foreach($data['banner_schedule'] as $key => $schedule)
	        	{
	        		if(date($schedule['to_date']) < date($schedule['from_date']))
	        		{
	        			$error = true;
                    	$this->_getSession()->addError(Mage::helper('magify_bannermanager')->__('Banner Schedule %s to date is before from date.', $key));
	        		}
	        	}
	        	if($error)
	        	{
	        		$this->_getSession()->setFormData($data);
            		$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id'), '_current' => true));
            		return;
	        	}
	        }
            $model->setData($data)
            	->saveLocations($data, $oldData)
            	->saveResources();
                                    
			Mage::dispatchEvent('magify_bannermanager_banner_prepare_save', array('model' => $model, 'request' => $this->getRequest()));
            
	        //Try to save it
            try {
                // save the data
                $model->save();

                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('magify_bannermanager')->__('The banner has been saved.'));
                // clear previously saved data from session
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                // check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId(), '_current' => true));
                    return;
                }
                // go to grid
                $this->_redirect('*/*/');
                return;

            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
            catch (Exception $e) {
                $this->_getSession()->addException($e,
                    Mage::helper('magify_bannermanager')->__('An error occurred while saving the banner.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            return;
        }
        $this->_redirect('*/*/');
    }
    
    /**
     * Delete action
     */
    public function deleteAction()
    {
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('id')) {
            $title = "";
            try {
                // init model and delete
                $model = Mage::getModel('magify_bannermanager/banner');
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('magify_bannermanager')->__('The banner has been deleted.'));
                // go to grid
                Mage::dispatchEvent('magify_bannermanager_banner_delete', array('title' => $title, 'status' => 'success'));
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::dispatchEvent('magify_bannermanager_banner_delete', array('title' => $title, 'status' => 'fail'));
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('magify_bannermanager')->__('Unable to find a banner to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }
    
    /**
     * Get banner link block
     */
    public function linkAction()
    {
    	$this->_initBanner();
        $this->loadLayout();
        $this->getLayout()->getBlock('magify.bannermanager.edit.tab.link');
        $this->renderLayout();
    } 

    public function linkcmspageAction()
    {
    	$this->_initBanner(); 
        $this->loadLayout();
        $this->getLayout()->getBlock('magify.bannermanager.csmpage');
        $this->renderLayout();
    }
    
    public function locationscategoriesJsonAction()
    {
    	$this->_initBanner();
    	$this->getResponse()->setBody(
            $this->getLayout()->createBlock('magify_bannermanager/adminhtml_bannermanager_edit_tab_locations_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }
        
    /**
     * Ajax get associated products grid and serializer block
     */
    public function productsGridAction()
    {
    	$this->_initBanner();
        $this->loadLayout();
        $this->getLayout()->getBlock('magify.bannermanager.edit.tab.products')
            ->setProducts($this->getRequest()->getPost('banner_products', null));
        $this->renderLayout();
    }
    
    /**
     * Ajax get location products grid and serializer block
     */
    public function locationsproductsGridAction()
    {
    	$this->_initBanner();
        $this->loadLayout();
        $this->getLayout()->getBlock('magify.bannermanager.edit.tab.lacations.tabs.products')
            ->setProducts($this->getRequest()->getPost('banner_locations_products', null));
        $this->renderLayout();
    }
    
    /**
     * Ajax get location pages grid and serializer block
     */
    public function locationspagesGridAction()
    {
    	$this->_initBanner();
        $this->loadLayout();
        $this->getLayout()->getBlock('magify.bannermanager.edit.tab.locations.tabs.pages')
            ->setPages($this->getRequest()->getPost('banner_locations_pages', null));
        $this->renderLayout();
    }
    
    /**
     * Ajax get location pages grid and serializer block
     */
    public function locationscustomGridAction()
    {
    	$this->_initBanner();
        $this->loadLayout();
        $this->getLayout()->getBlock('magify.bannermanager.edit.tab.locations.tabs.custom')
            ->setCustom($this->getRequest()->getPost('banner_locations_custom', null));
        $this->renderLayout();
    }
    
    /**
     * Get banner history log for ajax request
     */
    public function historyAction()
    {
    	$this->_initBanner();
        $this->loadLayout();
        $this->getLayout()->getBlock('magify.bannermanager.edit.tab.history');
        $this->renderLayout();
    }
}   