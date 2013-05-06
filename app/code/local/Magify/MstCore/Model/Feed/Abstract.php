<?php
/*******************************************
Magify

@category Magify
@copyright Copyright (C) 2013 Magify (http://magify.com)
*******************************************/

class Magify_MstCore_Model_Feed_Abstract extends Mage_Core_Model_Abstract
{
    public function getFeed($url, $params)
    {
        $curl = new Varien_Http_Adapter_Curl();
        $curl->write(Zend_Http_Client::POST, $url, '1.1', array(), http_build_query($params));
        $data = $curl->read();

        if ($data === false) {
            return false;
        }

        $data = preg_split('/^\r?$/m', $data, 2);
        $data = trim($data[1]);
        $curl->close();
        
        try {
            $xml = new SimpleXMLElement($data);
        }
        catch (Exception $e) {
            return false;
        }

        return $xml;
    }

    public function getDate($rssDate)
    {
        return gmdate('Y-m-d H:i:s', strtotime($rssDate));
    }
}