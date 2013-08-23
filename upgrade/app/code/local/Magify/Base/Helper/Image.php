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
 * @package     Magify_Base
 * @copyright   Copyright (c) 2012 Magify Commerce (http://www.magify.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Magify_Base_Helper_Image extends Mage_Catalog_Helper_Image
{
	public function resizeImage($name, $width = 100, $height = 100, $path = null, $frame = false, $quality = 100, $background = array(255, 255, 255))
	{
		$path = str_replace('/', DS, $path);
		$fullpath = Mage::getBaseDir('media') . DS . $path . DS . $name;
		
		$colorPath = implode('-', $background);
		
		$resizepath = $width . 'x' . $height;
		$fullresizepath = Mage::getBaseDir('media') . DS . $path . DS . 'cache' . DS . $resizepath .  DS . $colorPath . DS . $name;
		if(file_exists($fullpath) && !file_exists($fullresizepath))
		{
			$imageObj = new Varien_Image($fullpath);
			
			$imageObj->keepFrame($frame);
						
			$imageObj->constrainOnly(true);
			$imageObj->backgroundColor($background);
			$imageObj->keepAspectRatio(true);
	        $imageObj->quality($quality);
	        $imageObj->resize($width, $height);
			$imageObj->save($fullresizepath);
		}
	
		$path = str_replace(DS, '/', $path);
		return Mage::getBaseUrl('media') . $path . '/cache/' . $resizepath . '/' . $colorPath . '/' . $name;
	}			
}