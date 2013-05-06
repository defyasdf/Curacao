<?php
/**
 * @copyright  Copyright (c) 2010-2013 Magify (http://www.magify.com)
 */  
class Magify_Conf_Model_Source_ViewerPosition extends Varien_Object
{
	public function toOptionArray()
	{
	    $hlp = Mage::helper('mconf');
		return array(
			array('value' => 'right', 'label' => $hlp->__('On Right')),
			array('value' => 'left',  'label' => $hlp->__('On Left')),
		);
	}
	
}