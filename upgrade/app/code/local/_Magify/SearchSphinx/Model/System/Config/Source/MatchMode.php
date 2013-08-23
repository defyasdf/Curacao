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
class Magify_SearchSphinx_Model_System_Config_Source_MatchMode
{
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>Mage::helper('searchsphinx')->__('Matches all query words')),
            array('value' => 1, 'label'=>Mage::helper('searchsphinx')->__('Matches any of the query words')),
            array('value' => 2, 'label'=>Mage::helper('searchsphinx')->__('Matches query as a phrase, requiring perfect match')),
            array('value' => 3, 'label'=>Mage::helper('searchsphinx')->__('Matches query as a boolean expression')),
            array('value' => 4, 'label'=>Mage::helper('searchsphinx')->__('Matches query as an expression in Sphinx internal query language')),
        );
    }

    public function toArray()
    {
        return array(
            0 => Mage::helper('searchsphinx')->__('Matches all query words'),
            1 => Mage::helper('searchsphinx')->__('Matches any of the query words'),
            2 => Mage::helper('searchsphinx')->__('Matches query as a phrase, requiring perfect match'),
            3 => Mage::helper('searchsphinx')->__('Matches query as a boolean expression'),
            4 => Mage::helper('searchsphinx')->__('Matches query as an expression in Sphinx internal query language'),
        );
    }

}
