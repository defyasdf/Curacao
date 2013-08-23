<?php
class Magify_SearchAutocomplete_Model_System_Config_Source_Attribute
{
    public function toOptionArray()
    {
        $productAttributeCollection = Mage::getResourceModel('catalog/product_attribute_collection');
        $productAttributeCollection->addIsSearchableFilter();
        $productAttributeCollection->addFieldToFilter('backend_type', array('varchar', 'text'));

        $values = array();
        $values['---'] = array(
            'value' => '',
            'label' => '',
        );

        foreach($productAttributeCollection as $attribute) {
            $values[$attribute->getAttributeCode()] = array(
                'value' => $attribute->getAttributeCode(),
                'label' => $attribute->getFrontendLabel(),
            );
        }

        return $values;
    }
}
