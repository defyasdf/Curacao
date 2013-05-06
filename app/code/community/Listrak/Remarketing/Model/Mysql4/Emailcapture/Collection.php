<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Mysql4_Emailcapture_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
	
	protected function _construct()
    {
        $this->_init('listrak/emailcapture');
    }
}
