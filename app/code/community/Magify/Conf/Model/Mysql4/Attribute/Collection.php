<?php
/**
* @author Magify Team
* @copyright Magify
* @package Magify_Conf
*/
class Magify_Conf_Model_Mysql4_Attribute_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('mconf/attribute');
    }
}