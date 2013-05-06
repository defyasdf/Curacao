<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Cms
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://www.magentocommerce.com/license/enterprise-edition
 */

/**
 * IMPORTANT
 * Include the core file to be overridden
 */
require_once("Mage/Cms/controllers/IndexController.php");

/**
 * Custom Curacao Cms index controller
 *
 * @category   Mage
 * @package    Mage_Cms
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Curacao_Cms_IndexController extends Mage_Cms_IndexController
{
    /**
     * Render CMS 404 Not found page
     *
     * @param string $coreRoute
     */
    public function noRouteAction($coreRoute = null)
    {
    	$this->proc_404_routing($coreRoute);
        parent::noRouteAction($coreRoute);
    }
    
    private function proc_404_routing($coreRoute)
    {
    	$rewrite = new Mage_Core_Model_Url_Rewrite;
    	$resource = new Mage_Core_Model_Resource_Url_Rewrite;
    	$url = $_SERVER['REQUEST_URI'];
    	
    	//first make sure there isn't a leading slash; remove if it is there
    	if($url['0'] == '/')
    	{
    		$len = strlen($url);
    		$url = substr($url, 1, $len);
    	}
    	
    	//verify the url is actually bad
    	$connection = Mage::getSingleton('core/resource')->getConnection('core_read');
    	$sql = "SELECT store_id FROM core_url_rewrite WHERE ".$connection->quoteInto('request_path = ?', $url);
    	$url_data = $connection->fetchRow($sql);
    	if(!$url_data)
    	{
    		return;
    	}
    	
    	$current_store_code = Mage::app()->getStore()->getCode();
    	$sql = "SELECT code FROM core_store WHERE ".$connection->quoteInto('store_id = ?', $url_data['store_id']);
    	$new_store_code = $connection->fetchRow($sql); 
    	if(!$new_store_code)
    	{
    		return;
    	}
    	
    	//redirect user to new store to resolve the URL snafu
    	$new_url = '/'.$url.'?___store='.$new_store_code['code'].'&___from_store='.$current_store_code;
    	header('Location: '.$new_url);
    	exit;
    	//print_r($url_data);
    	//exit;
    }
}
