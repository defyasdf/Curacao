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

class Magify_Misspell_Adminhtml_System_ActionController extends Mage_Adminhtml_Controller_Action
{
    public function reindexAction()
    {
        try {
            $cntWords = Mage::getModel('misspell/indexer')->reindexAll();
            $this->getResponse()->setBody('Reindex completed! Total words: '.$cntWords);
        } catch(Exception $e) {
            $this->getResponse()->setBody(nl2br($e->getMessage()));
        }
    }
}