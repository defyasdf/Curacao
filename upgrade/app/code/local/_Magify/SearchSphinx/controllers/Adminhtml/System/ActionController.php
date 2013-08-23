<?php
/*******************************************
Magify
This source file is subject to the Magify Software License, which is available at http://magify.com/license/.
Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
If you wish to customize this module for your needs
Please refer to http://www.magentocommerce.com for more information.
@category Magify
@copyright Copyright (C) 2013 Magify (http://magify.com.ua)
*******************************************/
class Magify_SearchSphinx_Adminhtml_System_ActionController extends Mage_Adminhtml_Controller_Action
{
    protected function _getEngine()
    {
        return Mage::getSingleton('searchsphinx/engine_sphinx');
    }

    public function reindexAction()
    {
        $result = array();
        try {
            $this->_getEngine()->reindex();

            $result['message'] = Mage::helper('searchsphinx')->__('Index has been successfully rebuilt');
        } catch(Exception $e) {
            $result['message'] = nl2br($e->getMessage());
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function reindexdeltaAction()
    {
        try {
            $this->_getEngine()->reindexDelta();

            $this->getResponse()->setBody('Reindex Delta completed!');
        } catch(Exception $e) {
            $this->getResponse()->setBody(nl2br($e->getMessage()));
        }
    }

    public function stopstartAction()
    {
        try {
            $result = array();
            if ($this->_getEngine()->isSearchdRunning()) {
                $this->_getEngine()->stop();
                $result['message']   = Mage::helper('searchsphinx')->__('Stopped');
                $result['btn_label'] = Mage::helper('searchsphinx')->__('Start Sphinx daemon');
            } else {
                $this->_getEngine()->start();
                $result['message']   = Mage::helper('searchsphinx')->__('Launched');
                $result['btn_label'] = Mage::helper('searchsphinx')->__('Stop Sphinx daemon');
            }
        } catch(Exception $e) {
            $result['message'] = nl2br($e->getMessage());
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

}