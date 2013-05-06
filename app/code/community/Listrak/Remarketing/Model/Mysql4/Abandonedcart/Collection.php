<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Mysql4_Abandonedcart_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public $_prepareForReport = false;
	
    protected function _construct()
    {
        $this->_init('listrak/abandonedcart');
    }

	protected function _initSelect()
    {
    	parent::_initSelect();
		
    	$this->getSelect()
        	->join(array('q'=>$this->getTable('sales/quote')), 
				'main_table.quote_id = q.entity_id', 
				array('items_qty','grand_total'))
			->where('q.items_count > 0 AND q.is_active = 1');
	}
	
	public function addStoreFilter($storeIds)
    {
        $this->getSelect()->where('main_table.store_id IN (?)', $storeIds);
        return $this;
    }
	
	protected function _afterLoad() {
        foreach ($this->_items as $item) {
        	$item->afterLoad();
			if($this->_prepareForReport === true) {
				$item->prepareForReport();
			}
		}
		
		return parent::_afterLoad();
	}
}
