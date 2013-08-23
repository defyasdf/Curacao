<?php
/**
 * Magextend
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magextend.com/LICENSE.txt
 *
 * @category   Magextend
 * @author     Baptiste Cutajar
 * @copyright  Copyright (c) 2008-2010 magextend (http://www.magextend.com)
 * @license    http://www.magextend.com/LICENSE.txt
 */
?>
<?php
class Magify_AjaxLogin_Model_Observerloginpost
{
	public function __construct()
	{
	}

	public function check_login($observer)
	{
		if(isset($_COOKIE['ajax1235']) && $_COOKIE['ajax1235'] == 'ok')
		{
			$message = Mage::getSingleton('customer/session')->getMessages()->getLastAddedMessage()->getCode();
			$type = Mage::getSingleton('customer/session')->getMessages()->getLastAddedMessage()->getType();

			if($type != 'success')
				echo 'error_login_ko[1m2e3s4s5a6g7e8]'.$message.'[/1m2e3s4s5a6g7e8]';
			else			
				echo 'login_ok[1m2e3s4s5a6g7e8]'.$message.'[/1m2e3s4s5a6g7e8]';		
		}

		return $this;
	}
}
?>
