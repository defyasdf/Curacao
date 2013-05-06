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
class Magify_BannerManager_Adminhtml_MagifyBannermanager_GroupsController extends Mage_Adminhtml_Controller_Action
{
	protected $_model;
	
	protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('cms/magifybannermanager')
            ->_addBreadcrumb(Mage::helper('magify_bannermanager')->__('Banner Manager'), Mage::helper('magify_bannermanager')->__('Banner Groups'));
        return $this;
    }
    
    protected function _initBannerGroup()
    {
        $id = $this->getRequest()->getParam('id');
		$model = Mage::getModel('magify_bannermanager/banner_group')->load($id);
		$this->_model = $model;
        Mage::register('magify_bannermanager_current_banner_group', $model);
	}
        
    public function indexAction()
	{
        $this->_title(Mage::helper('magify_bannermanager')->__('CMS') )
        	->_title(Mage::helper('magify_bannermanager')->__('Banner Manager'))
        	->_title(Mage::helper('magify_bannermanager')->__('Banner Groups'));
        	$this->_initAction();       
        $this->renderLayout();
    }
    
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('magify_bannermanager/adminhtml_bannermanager_groups_grid')->toHtml()
        );
        $this->renderLayout();
    }
    
    public function newAction()
    {
        $this->editAction();
    }

    public function editAction()
    {
        $this->_title(Mage::helper('magify_bannermanager')->__('CMS') )
        	->_title(Mage::helper('magify_bannermanager')->__('Banners'))
        	->_title(Mage::helper('magify_bannermanager')->__('Manage Banner Groups'));
        	
        //Get ID and create model
        $id = $this->getRequest()->getParam('id');
		$model = Mage::getModel('magify_bannermanager/banner_group');
				
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
		
        $this->_title($model->getId() ? $model->getTitle() : Mage::helper('magify_bannermanager')->__('New Group'));
                
       	$version = substr(Mage::getVersion(), 0, 3);
        if (($version=='1.4' || $version=='1.5')) {
        	$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        
        //3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if(!empty($data))
		{
            $model->setData($data);
        }        

        //print_r($model->getData());//die();
        
        //4. Register model to use later in blocks
        Mage::register('magify_bannermanager_current_banner_group', $model);

        //5. Build edit form
        $this->_initAction()
          ->_addBreadcrumb(
                $id ? Mage::helper('magify_bannermanager')->__('Edit Group')
                    : Mage::helper('magify_bannermanager')->__('New Group'),
                $id ? Mage::helper('magify_bannermanager')->__('Edit Group')
                    : Mage::helper('magify_bannermanager')->__('New Group'));
                    
		$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
		
		$this->renderLayout();

    }

    public function saveAction()
    {
        //Check if data sent
        if($data = $this->getRequest()->getPost())
		{
            $model = Mage::getModel('magify_bannermanager/banner_group');
            
            $oldData = null;
            if($id = $this->getRequest()->getParam('id'))
			{
				$model->load($id);
				$oldData = $model->getData();
            }
            
            //print_r($data);
                
	        if(isset($data['child_banners']))
	        {
	            $data['child_banners'] = Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['child_banners']);
	        }
            
	        if(isset($data['group_schedule']))
	        {
	        	$error = false;
	        	foreach($data['group_schedule'] as $key => $schedule)
	        	{
	        		if(strtotime($schedule['to_date']) < strtotime($schedule['from_date']))
	        		{
	        			$error = true;
                    	$this->_getSession()->addError(Mage::helper('magify_bannermanager')->__('Group Schedule %s to date is before from date.', $key));
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
            	->saveLocations($data, $oldData);
	        	        	        
            //print_r($model->getData());die(0);
                        
			Mage::dispatchEvent('magify_bannermanager_banner_group_prepare_save', array('model' => $model, 'request' => $this->getRequest()));
            
	        //Try to save it
            try {
                // save the data
                $model->save();

                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('magify_bannermanager')->__('The banner group has been saved.'));
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
                    Mage::helper('magify_bannermanager')->__('An error occurred while saving the banner group.'));
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
                $model = Mage::getModel('magify_bannermanager/banner_group');
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('magify_bannermanager')->__('The banner group has been deleted.'));
                // go to grid
                Mage::dispatchEvent('magify_bannermanager_banner_group_delete', array('title' => $title, 'status' => 'success'));
                $this->_redirect('*/*/');
                return;

            } catch (Exception $e) {
                Mage::dispatchEvent('magify_bannermanager_banner_group_delete', array('title' => $title, 'status' => 'fail'));
                // display error message
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('magify_bannermanager')->__('Unable to find a banner group to delete.'));
        // go to grid
        $this->_redirect('*/*/');
    }
    
    public function childrenAction()
    {
    	$this->_initBannerGroup();
    	$this->loadLayout();
        $this->getLayout()->getBlock('magify.bannermanager.group.tab.banners')
            ->setChildBanners($this->getRequest()->getPost('child_banners', null));
        $this->renderLayout();
    }
    
    public function locationscategoriesJsonAction()
    {
    	$this->_initBannerGroup();
    	$this->getResponse()->setBody(
            $this->getLayout()->createBlock('magify_bannermanager/adminhtml_bannermanager_groups_edit_tab_locations_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }
    
    /**
     * Ajax get location products grid and serializer block
     */
    public function locationsproductsGridAction()
    {
    	$this->_initBannerGroup();
        $this->loadLayout();
        $this->getLayout()->getBlock('magify.bannermanager.groups.edit.tab.lacations.tabs.products')
            ->setProducts($this->getRequest()->getPost('groups_locations_products', null));
        $this->renderLayout();
    }
    
    /**
     * Ajax get location pages grid and serializer block
     */
    public function locationspagesGridAction()
    {
    	$this->_initBannerGroup();
        $this->loadLayout();
        $this->getLayout()->getBlock('magify.bannermanager.groups.edit.tab.locations.tabs.pages')
            ->setPages($this->getRequest()->getPost('groups_locations_pages', null));
        $this->renderLayout();
    }
    
    /**
     * Ajax get location pages grid and serializer block
     */
    public function locationscustomGridAction()
    {
    	$this->_initBannerGroup();
        $this->loadLayout();
        $this->getLayout()->getBlock('magify.bannermanager.groups.edit.tab.locations.tabs.custom')
            ->setCustom($this->getRequest()->getPost('groups_locations_custom', null));
        $this->renderLayout();
    }
     
}   