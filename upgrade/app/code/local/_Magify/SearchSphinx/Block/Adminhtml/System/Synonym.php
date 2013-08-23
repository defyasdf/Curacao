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
class Magify_SearchSphinx_Block_Adminhtml_System_Synonym extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    public function __construct()
    {
        $this->addColumn('word', array(
            'label' => Mage::helper('adminhtml')->__('Word'),
            'style' => 'width:120px',
        ));
        $this->addColumn('synonyms', array(
            'label' => Mage::helper('adminhtml')->__('Synonyms (comma delimiter)'),
            'style' => 'width:300px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Synonyms');
        parent::__construct();
    }
}