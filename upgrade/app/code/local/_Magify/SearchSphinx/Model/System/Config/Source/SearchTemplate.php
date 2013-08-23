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
class Magify_SearchSphinx_Model_System_Config_Source_SearchTemplate
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'and',
                'label' => Mage::helper('searchsphinx')->__('AND matching (word1 & word2 & word3...)')
            ),
            array(
                'value' => 'or',
                'label' => Mage::helper('searchsphinx')->__('OR matching (word1 | word2 | word3...)')
            ),
            array(
                'value' => 'quorum',
                'label' => Mage::helper('searchsphinx')->__('Quorum matching ("query" / n)')
            ),
        );
    }
}
