<?php
require_once 'abstract.php';
class Magify_Shell_SearchIndex extends Mage_Shell_Abstract
{
    public function run()
    {
        $resultHelper = Mage::helper('searchindex/result');
        echo '<pre>';
        print_r($resultHelper->query('abo'));
    }

    public function _validate()
    {

    }
}

$shell = new Magify_Shell_SearchIndex();
$shell->run();
