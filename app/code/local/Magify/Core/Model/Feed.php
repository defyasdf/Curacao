<?php
/**
 * Magify Commerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magify.com so we can send you a copy immediately.
 *
 * @category    Magify
 * @package     Magify_Core
 * @copyright   Copyright (c) 2012 Magify Commerce (http://www.magify.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Magify_Core_Model_Feed extends Mage_AdminNotification_Model_Feed
{
    const XML_FEED_URL_PATH     = 'magify_core/feed/url';
    const XML_FREQUENCY_PATH    = 'magify_core/feed/frequency';
        
    public function getLastUpdate(){
        return Mage::app()->loadCache('magify_core_notifications_lastcheck');
    }

    public function setLastUpdate()
    {
        Mage::app()->saveCache(time(), 'magify_core_notifications_lastcheck');
        return $this;
    }
    
    public function getFrequency()
    {
        return Mage::getStoreConfig(self::XML_FREQUENCY_PATH) * 3600;
    }
}