<?php
/*******************************************
Magify

@category Magify
@copyright Copyright (C) 2013 Magify (http://magify.com)
*******************************************/


class Magify_MstCore_Block_Config extends Mage_Adminhtml_Block_Template
{
    protected function _prepareLayout()
    {
        $this->_section = $this->getAction()->getRequest()->getParam('section', false);

        parent::_prepareLayout();
    }

    protected function _toHtml()
    {
        if ($this->_section == 'mstcore_store') {
            return parent::_toHtml();
        } else {
            return '';
        }
    }

    public function getStoreHtml()
    {
        $url = Magify_MstCore_Helper_Config::STORE_URL;

        $html = Mage::app()->loadCache($url);
        
        if (!$html) {
            $html = $this->_loadUrl($url);
            Mage::app()->saveCache($html, $url);
        }

        return $html;
    }

    protected function _loadUrl($url)
    {
        $curl = new Varien_Http_Adapter_Curl();
        $curl->setConfig(array('timeout' => 30));
        $curl->write(Zend_Http_Client::GET, $url, '1.0');

        $text = $curl->read();
        $text = preg_split('/^\r?$/m', $text, 2);
        $text = trim($text[1]);

        return $text;
    }
}