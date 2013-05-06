<?php
/**
* @author Magify Team
* @copyright Magify
* @package Magify_Conf
*/
class Magify_Conf_Block_Catalog_Product_Tooltip extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('mconf/tooltip.phtml');
    }
}

