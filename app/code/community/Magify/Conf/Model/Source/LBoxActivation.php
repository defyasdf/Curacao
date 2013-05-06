<?php
/**
 * @copyright  Copyright (c) 2010-2013 Magify (http://www.magify.com)
 */  
class Magify_Conf_Model_Source_LBoxActivation extends Varien_Object
{
	public function toOptionArray()
	{
	    $hlp = Mage::helper('mconf');
		return array(
			array('value' => 'mouse', 'label' => $hlp->__('On Mouse Over')),
			array('value' => 'click',  'label' => $hlp->__('On Click')),
		);
	}
	
}