<?php
ini_set('apc.cache_by_default','Off');
if ($_REQUEST['save']) {
    print "<pre>";
    print_r($_REQUEST);
    print "</pre>";
}

$jsInclude = "product_order.js.php";
$bc[] = array('text' => 'Product Order');
_cg("page_title", "Product Order");
?>