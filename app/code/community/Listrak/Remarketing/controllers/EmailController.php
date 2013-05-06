<?php
// Listrak Remarketing Magento Extension Ver. 1.0.0
// © 2011 Listrak, Inc.

class Listrak_Remarketing_EmailController extends Mage_Core_Controller_Front_Action
{
	public function indexAction() {
		try {
			$email = $this->getRequest()->getParam('email');
			
			if(!Zend_Validate::is($email, 'EmailAddress')) {
				die("");
			}
			
			$emailcaptureId = $this->getRequest()->getParam('cid');
			$session = Mage::getSingleton('listrak/session')->init();
			
			$emailcapture = Mage::getModel('listrak/emailcapture')->load($emailcaptureId);

			if($emailcapture->getId()) {
				$session->getResource()->insertEmail($session, $email, $emailcaptureId);

				$result = array('status' => true);
			} else {
				$result = array('status' => false);
			}

			header('Content-type: application/json');
			die(json_encode($result));
		} catch (Exception $e) {
			Mage::getModel("listrak/log")->addException($e);
			die("");
		}
	}
}
