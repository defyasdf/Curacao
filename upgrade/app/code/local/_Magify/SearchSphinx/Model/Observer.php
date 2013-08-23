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

class Magify_SearchSphinx_Model_Observer
{
    /**
     * run sphinx reindex
     */
    public function reindex()
    {
        Mage::getModel('searchsphinx/sphinx_control')->reindex();
    }

    public function reindexDelta()
    {
        Mage::getModel('searchsphinx/sphinx_control')->reindexDelta();
    }
}