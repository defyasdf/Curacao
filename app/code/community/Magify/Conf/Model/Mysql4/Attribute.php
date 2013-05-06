<?php
/**
* @author Magify Team
* @copyright Magify
* @package Magify_Conf
*/
class Magify_Conf_Model_Mysql4_Attribute extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('mconf/attribute', 'entity_id');
    }
}