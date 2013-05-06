<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Mysql4_Log extends Mage_Core_Model_Mysql4_Abstract {
	
    protected $_read;

    protected $_write;
	
	protected function _construct()
    {
        $this->_init('listrak/log', 'id');
		$this->_read = $this->_getReadAdapter();
        $this->_write = $this->_getWriteAdapter();
    }
}