<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{

    /**
     * About action
     */
    public function aboutAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('gorilla/authorizenetcim');
        $this->_title('Gorilla')->_title('Authorize.net Customer Information Manager (CIM) Module')->_title('About');
        $this->_addContent($this->getLayout()->createBlock('authorizenetcim/adminhtml_about', 'authorizenetcim_about'));
        $this->renderLayout();
    }

}