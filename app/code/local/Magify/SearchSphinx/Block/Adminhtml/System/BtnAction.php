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
class Magify_SearchSphinx_Block_Adminhtml_System_BtnAction extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('searchsphinx/system/btn_action.phtml');
        }
        return $this;
    }

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $originalData = $element->getOriginalData();
        $this->addData(array(
            'button_label' => $this->_getBtnLabel($originalData),
            'html_id'      => $element->getHtmlId(),
            'ajax_url'     => Mage::getSingleton('adminhtml/url')->getUrl('searchsphinx/adminhtml_system_action/'.$originalData['button_action'])
        ));

        return $this->_toHtml();
    }

    protected function _getBtnLabel($originalData)
    {
        $label = $originalData['button_label'];

        switch ($originalData['button_action']) {
            case 'stopstart':
                $engine = Mage::getSingleton('searchsphinx/engine_sphinx');
                if ($engine->isSearchdRunning()) {
                    $label = 'Stop Sphinx daemon';
                } else {
                    $label = 'Start Sphinx daemon';
                }
                break;
        }

        return Mage::helper('searchsphinx')->__($label);
    }
}
