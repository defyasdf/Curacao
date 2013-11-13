<?php

class Curacao_Creditapp_IndexController extends Mage_Core_Controller_Front_Action {

    public function step3PostAction() {
        Mage::getModel('creditapp/creditmodel')->updateCreditAppAndActivity($_POST);
        $customer = Mage::getModel('creditapp/creditmodel')->loadByAppId($_POST['appid']);
        $credit = Mage::helper('creditapp')->WebCustomerApplication($customer);
        $re = $credit->WebCustomerApplicationResult;
        $final = explode("|", $re);
        Mage::getModel('creditapp/creditmodel')->updateAfterWebCustomerApplication($final, $_POST['appid']);
        Mage::getSingleton('core/session')->setFinal($final);
        Mage::getSingleton('core/session')->setStep(2);
        $this->_redirect('*/*/index');
    }

    public function step2PostAction() {
        $s = '1';
        if (isset($_POST['qsetid'])) {
            $result = Mage::helper('creditapp')->Auth_Continue($_POST);
            $re = $result->Auth_ContinueResult;
            if ($re->Result == 'PASSED') {
                Mage::getModel('creditapp/creditmodel')->updateAfterAuthContinueResult($_POST['appid'], Mage::getSingleton('core/session')->getTrackid());
            }
        }
        Mage::getSingleton('core/session')->setStep("2");
        $this->_redirect('*/*/index');
    }

    public function step1PostAction() {
        if (Mage::getSingleton('core/session')->getTrackid()) {
            Mage::getModel('creditapp/creditmodel')->markFirstPageCompleted(Mage::getSingleton('core/session')->getTrackid());
        }
        $postData = $this->getRequest()->getPost();
        

        if (isset($postData['psswd']) || isset($postData['psswd2'])) {
            $fname = $postData['fname'];
            $lname = $postData['lname'];
            $email = $postData['emailid'];
            $street = $postData['street1'];
            $street2 = $postData['street2'];
            $city = $postData['city'];
            $region = $postData['state'];
            $postcode = $postData['postcode'];

            $psswd = $postData['psswd'];
            $psswd2 = $postData['psswd2'];

            $customer = Mage::getModel('customer/customer');
            $customer->setWebsiteId(Mage::app()->getWebsite()->getId());
            $customer->loadByEmail($email);

            // -----------------------------------------------------------------------------------------------
            // Check if the email exist on the system.
            // -----------------------------------------------------------------------------------------------
            if (!$customer->getId()) {
                $customer->setEmail($email);    //set user data
                $customer->setFirstname($fname);
                $customer->setLastname($lname);
                $customer->setPassword($psswd);

                try {
                    $customer->save();
                    $customer->setConfirmation(null);
                    $customer->save();
                    $customer->sendNewAccountEmail();
                } catch (Exception $ex) {
                    
                }
            }
        }

        $state = '';

        if (isset($postData['id_type'])) {
            if ($postData['id_type'] == 'AU1' || $postData['id_type'] == 'AU2') {

                $state = 'CA';
            } else {
                $state = $postData['id_state'];
            }
        } elseif (isset($postData['id_state'])) {
            $state = $postData['id_state'];
        } else {
            $state = 'CA';  //default case
        }

        try {
            $result = Mage::helper('creditapp')->Auth_Init($postData);
            $re = $result->Auth_InitResult;
        } catch (Exception $ex) {
            $re = 0;
        }
	
        //this is throwing an error
        Mage::getSingleton('core/session')->setStep("1");
        Mage::getSingleton('core/session')->setRe($re);
        Mage::getSingleton('core/session')->setFname($postData['fname']);
        Mage::getSingleton('core/session')->setLname($postData['lname']);
        Mage::getSingleton('core/session')->setEmail($email);
        Mage::getSingleton('core/session')->setPassword($psswd);
        Mage::getSingleton('core/session')->setConfirmpass($psswd2);
       
        $results = array();
        $results['goto_section'] = 'verification';
        $results['update_section'] = array(
                        'name' => 'step2',
                        'html' => $this->_getVerificationHtml()
                    );

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($results));
    }

      /**
     * Get shipping method step html
     *
     * @return string
     */
    protected function _getVerificationHtml()
    {
        return "in step2 going";
        /*
        $layout = $this->getLayout();
        $update = $layout->getUpdate();
        $update->load('creditapp_index_step2');
        $layout->generateXml();
        $layout->generateBlocks();
        echo "<pre>";
        print_r($layout);
        exit;
        $output = $layout->getOutput();
        return $output;
        */
    }

    
    public function validateAction() {

        $credit = Mage::helper('creditapp')->ValidateAddress($postData);
        if ($credit->ValidateAddressResult == 1) {
            Mage::getModel('creditapp/creditmodel')->markValidated($postData['appid']);
        }
        echo $credit->ValidateAddressResult;
    }

    public function creditAction() {
        echo Mage::getModel('creditapp/creditmodel')->saveOrUpdateCreditApp($_POST, $_SERVER);
    }

    public function IndexAction() {

        $this->loadLayout();
        $this->getLayout()->getBlock("head")->setTitle($this->__("Credit Application"));
        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("home", array(
            "label" => $this->__("Home Page"),
            "title" => $this->__("Home Page"),
            "link" => Mage::getBaseUrl()
        ));

        $breadcrumbs->addCrumb("credit application", array(
            "label" => $this->__("Credit Application"),
            "title" => $this->__("Credit Application")
        ));

        $this->renderLayout();
    }

    /**
     * Checkout status block
     */
    public function progressAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        $this->loadLayout(false);
        $this->renderLayout();
    }

    protected function _ajaxRedirectResponse()
    {
        $this->getResponse()
            ->setHeader('HTTP/1.1', '403 Session Expired')
            ->setHeader('Login-Required', 'true')
            ->sendResponse();
        return $this;
    }

    /**
     * Validate ajax request and redirect on failure
     *
     * @return bool
     */
    protected function _expireAjax()
    {
      return true;
	  
	    if (!$this->getOnepage()->getQuote()->hasItems()
            || $this->getOnepage()->getQuote()->getHasError()
            || $this->getOnepage()->getQuote()->getIsMultiShipping()) {
            $this->_ajaxRedirectResponse();
            return true;
        }
        $action = $this->getRequest()->getActionName();
        if (Mage::getSingleton('checkout/session')->getCartWasUpdated(true)
            && !in_array($action, array('index', 'progress'))) {
            $this->_ajaxRedirectResponse();
            return true;
        }

        return false;
    }

}
