<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_Model_Mysql4_Abandonedcart extends Mage_Core_Model_Mysql4_Abstract
{
    protected $_read;

    protected $_write;

    public function _construct()
    {    
        $this->_init('listrak/session','id');
		$this->_read = $this->_getReadAdapter();
        $this->_write = $this->_getWriteAdapter();
    }
	
	protected function _afterLoad(Mage_Core_Model_Abstract $object) {
		$object->setSession(Mage::getModel("listrak/session")->load($object->getId()));
		
		$this->loadCart($object);
		
		return parent::_afterLoad($object);
	}
	
	protected function loadCart(Mage_Core_Model_Abstract $object) {
		$product_fields = array(
			'entity_id'=>'', 'sku'=>'', 'name'=>'', 
			'special_price' => '', 'special_from_date' => '', 'special_to_date' => '',
			'cost'=>'', 'description'=>'', 
			'short_description'=>'', 'weight'=>'', 'url_key'=>'', 'url_path'=>'', 
			'image'=>'', 'small_image'=>'', 'thumbnail'=>'', 'qty'=>'');
		
		$fields = array(
			'item_id' => 'q.item_id', 
			'quote_id' => 'q.quote_id',
			'product_id' => 'q.product_id',
			'qty' => 'q.qty',
			'price' => 'q.price');
			
		$products = array();
		
		$qiSelect = $this->_read->select()
			->from(array('q' => $this->getTable('sales/quote_item')), $fields)
			->where('q.quote_id = ?', $object->getQuoteId())
			->where('q.product_type <> "configurable"');
        
		$qiResult = $this->_read->fetchAll($qiSelect);
		
		foreach($qiResult as $qi) {
			$product = Mage::getModel('catalog/product')->load($qi['product_id']);
			$qitem = array_intersect_key($product->toArray(),$product_fields);
			$qitem["qty"] = $qi["qty"];
			$qitem["price"] = $qi["price"];
			$products[] = $qitem;
		}
		
		$object->setProducts($products);
	}
}
