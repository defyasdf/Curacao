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

//set_include_path(get_include_path().PS.Mage::getBaseDir('lib').DS.'lessphp');
require_once(Mage::getBaseDir('lib') . DS . 'lessphp' . DS . 'lessc.inc.php');

class Magify_Core_Block_Page_Html_Head extends MageWorx_SeoSuite_Block_Page_Html_Head // Mage_Page_Block_Html_Head
{
    /**
     * Add Less file to HEAD entity
     *
     * @param string $name
     * @param string $params
     * @return Magify_LESSJS_Block_Page_Html_Head
     */
	public function addLess($name, $params = "", $if = "")
    {    	
    	$parsed = str_replace('.less', '_lessparsed.css', $name);
    	$orgiFile = Mage::getDesign()->getFilename($name, array('_type' => 'skin'));
    	$parsedFile = str_replace('.less', '_lessparsed.css', $orgiFile);
    	
    	//Check if file contains styles
    	$contents = file_get_contents($orgiFile);
    	if(!strlen($contents)) return $this;
    	
    	//Check if file exists
    	if(!file_exists($orgiFile)) return $this;
    	
    	$orgiModified = filemtime($orgiFile);
    	
    	if(file_exists($parsedFile))
    	{
    		$parsedModified = filemtime($parsedFile);
    	}
    	else
    	{
    		$parsedModified = null;
    	}
    	
    	$parsedFiles = ($this->getParsedFiles()) ? $this->getParsedFiles() : array();
    	
    	//Check if file has been modified.
    	if((!$parsedModified && $orgiModified) || (($orgiModified) && ($orgiModified > $parsedModified)))
    	{
		    try
		    {
			    lessc::ccompile($orgiFile, $parsedFile);
			    
			}
			catch(Exception $e)
			{
			    exit('lessc fatal error:<br />'.$e->getMessage());
			}
    	}
    	
    	$parsedFiles[] = $parsedFile;
    	    	
    	$this->setParsedFiles($parsedFiles);
    	    	
        $this->addItem('skin_css', $parsed, $params, $if);
        
        return $this;
    }
}