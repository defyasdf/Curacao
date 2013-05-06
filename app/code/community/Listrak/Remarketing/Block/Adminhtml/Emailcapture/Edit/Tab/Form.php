<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Block_Adminhtml_EmailCapture_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('emailcapture_form', array('legend'=>Mage::helper('remarketing')->__('Field information')));

        $fieldset->addField('page', 'text', array(
            'label'     => Mage::helper('remarketing')->__('Page'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'page',
            'after_element_html' => '<p class="note"><span>For example: /checkout/onepage/index. Each URL has 3 parts. If yours does not have 3 parts, fill the last with "index". You can also use wildcard character "*" to capture field on all pages in the store.</span></p>'
        ));

        $fieldset->addField('field_id', 'text', array(
            'label'     => Mage::helper('remarketing')->__('Field ID'),
            'name'      => 'field_id',
			'required' 	=> true,
			'class' 	=> 'required-entry',
            'after_element_html' => '<p class="note"><span>Field id attribute. You can check it in HTML code preview.</span></p>'
        ));

        if (Mage::getSingleton('adminhtml/session')->getEmailCaptureData())
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getEmailCaptureData());
            Mage::getSingleton('adminhtml/session')->setEmailCaptureData(null);
        } elseif (Mage::registry('emailcapture_data')) {
            $form->setValues(Mage::registry('emailcapture_data')->getData());
        }
        return parent::_prepareForm();
    }
}