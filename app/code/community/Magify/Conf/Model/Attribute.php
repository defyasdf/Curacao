<?php
/**
* @author Magify Team
* @copyright Magify
* @package Magify_Conf
*/
class Magify_Conf_Model_Attribute extends Mage_Core_Model_Abstract
{
    const FLAGS_FOLDER = 'amflags';
    
    protected function _construct()
    {
        $this->_init('mconf/attribute');
    }
}
