<?php
/**
 * Gorilla AuthorizeNet CIM module
 *
 * @category     Gorilla
 * @copyright    Copyright (c) 2011-2012 Gorilla (http://www.gorillagroup.com)
 */
class Gorilla_AuthorizenetCim_Block_Adminhtml_About extends Mage_Adminhtml_Block_Abstract
{
    /**
     * Return about.txt content
     *
     */
    protected function _toHtml()
    {
        $aboutFile = Mage::getModuleDir('', 'Gorilla_AuthorizenetCim') . DS . 'about.txt';
        if (file_exists($aboutFile)) {
            $aboutContent = file_get_contents($aboutFile);
            echo str_replace("\n", "<br/>", $aboutContent);
        }
    }
}
 
