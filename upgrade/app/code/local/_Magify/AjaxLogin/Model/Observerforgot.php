<?php
class Magify_Ajaxlogin_Model_Observerforgot
{
	public function __construct()
	{
	}

	public function check_forgot($observer)
	{
		if(isset($_COOKIE['ajax1235']) && $_COOKIE['ajax1235'] == 'ok')
		{
			$message = Mage::getSingleton('customer/session')->getMessages()->getLastAddedMessage()->getCode();
			$type = Mage::getSingleton('customer/session')->getMessages()->getLastAddedMessage()->getType();

			if($type != 'success')
				echo 'error_forgot_ko[1m2e3s4s5a6g7e8]'.$message.'[/1m2e3s4s5a6g7e8]';
			else			
				echo 'forgot_ok[1m2e3s4s5a6g7e8]'.$message.'[/1m2e3s4s5a6g7e8]';	
		}

		return $this;
	}
}
?>
