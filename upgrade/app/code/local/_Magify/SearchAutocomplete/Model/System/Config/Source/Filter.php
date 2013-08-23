<?php
class Magify_SearchAutocomplete_Model_System_Config_Source_Filter
{
    public function toOptionArray()
    {
        $options = array(
            'category' => array(
                'value' => 'category',
                'label' => Mage::helper('searchautocomplete')->__('Category'),
            ),
            'attribute' => array(
                'value' => 'attribute',
                'label' => Mage::helper('searchautocomplete')->__('Attribute'),
            ),
            'none' => array(
                'value' => 'none',
                'label' => Mage::helper('searchautocomplete')->__('No Display'),
            ),
        );

        return $options;
    }
}
