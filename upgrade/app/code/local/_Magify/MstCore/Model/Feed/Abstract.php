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
    }

    public function getDate($rssDate)
    {
        return gmdate('Y-m-d H:i:s', strtotime($rssDate));
    }
}
