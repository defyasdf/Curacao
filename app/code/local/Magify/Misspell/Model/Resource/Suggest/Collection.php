<?php
/*******************************************
Magify
This source file is subject to the Magify Software License, which is available at http://magify.com/license/.
Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
If you wish to customize this module for your needs
Please refer to http://www.magentocommerce.com for more information.
@category Magify
@copyright Copyright (C) 2013 Magify (http://magify.com.ua)
*******************************************/

class Magify_Misspell_Model_Resource_Suggest_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * Constructor method
     */
    protected function _construct()
    {
        $this->_init('misspell/suggest');
    }
}