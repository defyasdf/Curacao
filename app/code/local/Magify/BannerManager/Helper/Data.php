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
 * @package     Magify_BannerManager
 * @copyright   Copyright (c) 2012 Magify Commerce (http://www.magify.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Magify_BannerManager_Helper_Data extends Magify_Base_Helper_Data
{
	public function getBannerImage($file, $resize = null, $background = array(255, 255, 255), $maxWidth = null, $maxHeight = null)
	{
		
		if(is_null($resize))
		{
			if($maxWidth && $maxHeight)
			{
				return Mage::helper('magify_base/image')
				->resizeImage($file, $maxWidth, $maxHeight, Magify_BannerManager_Model_Banner::BANNER_FILE_PATH, true, 100, $this->hex2RGB($background));
			}			
			
			return Mage::getBaseUrl('media') . Magify_BannerManager_Model_Banner::BANNER_FILE_PATH . '/' . $file;
		}
		
		return Mage::helper('magify_base/image')
		->resizeImage($file, $resize['width'], $resize['height'], Magify_BannerManager_Model_Banner::BANNER_FILE_PATH, false, 100, $this->hex2RGB($background));
	}
	
	public function hex2RGB($hexStr, $returnAsString = false, $seperator = ',')
	{
	    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
	    $rgbArray = array();
	    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
	        $colorVal = hexdec($hexStr);
	        $rgbArray[] = 0xFF & ($colorVal >> 0x10);
	        $rgbArray[] = 0xFF & ($colorVal >> 0x8);
	        $rgbArray[] = 0xFF & $colorVal;
	    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
	        $rgbArray[] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
	        $rgbArray[] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
	        $rgbArray[] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
	    } else {
	        return false; //Invalid hex color code
	    }
	    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
	}
	
	public function getContent($id){
	}
		
}