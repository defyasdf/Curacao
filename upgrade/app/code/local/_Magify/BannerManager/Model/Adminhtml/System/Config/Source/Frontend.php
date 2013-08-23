<?php
class Magify_BannerManager_Model_Adminhtml_System_Config_Source_Frontend
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
    	return Mage::getModel('magify_bannermanager/banner')->getAvailableFrontends();
    }
}