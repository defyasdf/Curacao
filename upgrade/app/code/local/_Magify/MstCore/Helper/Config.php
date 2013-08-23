<?php
/*******************************************
Magify

@category Magify
@copyright Copyright (C) 2013 Magify (http://magify.com)
*******************************************/


class Magify_MstCore_Helper_Config extends Mage_Core_Helper_Data
{
    const UPDATES_FEED_URL = 'http://magify.com/blog/category/updates/feed/';
    const STORE_URL        = 'http://magify.com/estore/';
}

if (!function_exists('pr')) {
    function pr($arr)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}