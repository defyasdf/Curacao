<?php
abstract class Magify_SearchIndex_Model_Engine
{
    abstract public function query($queryText, $store, $index);

    protected function _getReadAdapter()
    {
        return Mage::getSingleton('core/resource')->getConnection('core_read');
    }
}