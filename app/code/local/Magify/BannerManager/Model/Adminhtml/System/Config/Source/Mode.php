<?php
class Magify_BannerManager_Model_Adminhtml_System_Config_Source_Mode
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
            	'value' => Magify_BannerManager_Model_Config::MODE_AUTO_AND_MANUAL,
            	'label' => Mage::helper('magify_bannermanager')->__('Auto & Manual Grouping')
            ),
        	array(
            	'value' => Magify_BannerManager_Model_Config::MODE_AUTO,
            	'label' => Mage::helper('magify_bannermanager')->__('Only Auto Banner Grouping')
            ),
            array(
            	'value' => Magify_BannerManager_Model_Config::MODE_MANUAL,
            	'label' => Mage::helper('magify_bannermanager')->__('Only Manual Banner Grouping')
            ),
    	);
    }
}