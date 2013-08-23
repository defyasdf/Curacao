<?php
/*******************************************
Magify

@category Magify
@copyright Copyright (C) 2013 Magify (http://magify.com)
*******************************************/

class Magify_MstCore_Helper_Data extends Mage_Core_Helper_Data
{
    public function isModuleInstalled($modulename) {
        $modules = Mage::getConfig()->getNode('modules')->children();
        $modulesArray = (array)$modules;

        if(isset($modulesArray[$modulename]) && $modulesArray[$modulename]->is('active')) {
            return true;
        } else {
            return false;
        }

    }
    
    public function pr($arr, $ip = false, $die = false) {
        if (!$ip) {
            pr($arr);
        } elseif ($_SERVER['REMOTE_ADDR'] == $ip) {
            pr($arr);
            if ($die) {
                die();
            }
        }
    }
}

if (!function_exists('pr')) {
    function pr($arr)
    {
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
}