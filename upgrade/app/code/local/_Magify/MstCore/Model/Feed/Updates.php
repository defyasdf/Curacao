<?php
/*******************************************
Magify

@category Magify
@copyright Copyright (C) 2013 Magify (http://magify.com)
*******************************************/

class Magify_MstCore_Model_Feed_Updates extends Magify_MstCore_Model_Feed_Abstract
{
    public function check()
    {
        if (time() - Mage::app()->loadCache(Magify_MstCore_Helper_Config::UPDATES_FEED_URL) > 3 * 60 * 60) {
            $this->refresh();
        }
    }

    public function refresh()
    {
        $params = array();
        $params['domain'] = Mage::getBaseUrl();
        foreach (Mage::getConfig()->getNode('modules')->children() as $name => $module) {
            $params['modules'][$name] = (string) $module->version;
        }

        try {
            Mage::app()->saveCache(time(), Magify_MstCore_Helper_Config::UPDATES_FEED_URL);
            
            $xml = $this->getFeed(Magify_MstCore_Helper_Config::UPDATES_FEED_URL, $params);

            $items = array();
            if ($xml) {
                foreach ($xml->xpath('channel/item') as $item) {
                    $items[] = array(
                        'title'       => (string) $item->title,
                        'description' => (string) Mage::helper('core/string')->truncate(strip_tags($item->description), 255),
                        'url'         => (string) $item->link,
                        'date_added'  => (string) $this->getDate($item->pubDate),
                        'severity'    => 3,
                    );
                }
            }

            Mage::getModel('adminnotification/inbox')->parse($items);
        } catch (Exception $ex) { 
            Mage::logException($ex);
        }

        return $this;
    }
}