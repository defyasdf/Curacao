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

class Magify_Searchautocomplete_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function getAction()
    {
        $this->loadLayout();

        $query = Mage::helper('catalogsearch')->getQuery();
        $query->setStoreId(Mage::app()->getStore()->getId());
        $result = array();
        if ($query->getQueryText()) {
            if (Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->setId(0)
                    ->setIsActive(1)
                    ->setIsProcessed(1);
            } else {
                if ($query->getId()) {
                    $query->setPopularity($query->getPopularity() + 1);
                } else {
                    $query->setPopularity(1);
                }
                $query->prepare();
            }

            $collection = Mage::getSingleton('catalogsearch/layer')->getProductCollection();

            if ($this->getRequest()->getParam('cat')) {
                $categoryId = $this->getRequest()->getParam('cat');
                $collection->addCategoryFilter(Mage::getModel('catalog/category')->load($categoryId));
            }

            if ($this->getRequest()->getParam('attr')) {
                $attrCode = $this->getRequest()->getParam('attr');
                $collection->addAttributeToSelect($attrCode)
                    ->addFieldToFilter($attrCode, array('like' => '%'.$query->getQueryText().'%'));
            }

            $resultBlock = $this->getLayout()
                ->createBlock('searchautocomplete/result')
                ->setTemplate('searchautocomplete/autocomplete/result.phtml');

            $result['items']   = $resultBlock->toHtml();
            $result['suggest'] = $resultBlock->getSuggestQueries();

            $result['success'] = true;

            Mage::helper('catalogsearch')->getQuery()->save();
        } else {
            $result['success'] = false;
        }

        $this->getResponse()->setHeader('Content-Type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}
