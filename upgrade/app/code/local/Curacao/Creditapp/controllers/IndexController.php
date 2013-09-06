<?php
class Curacao_Creditapp_IndexController extends Mage_Core_Controller_Front_Action{
	
	public function step3PostAction(){
		
				
		Mage::getModel('creditapp/creditmodel')->updateCreditAppAndActivity($_POST);
						
		
		$customer = Mage::getModel('creditapp/creditmodel')->loadByAppId($_POST['appid']);
		
		$credit = Mage::helper('creditapp')->WebCustomerApplication($customer);
	
		$re = $credit->WebCustomerApplicationResult;	
		$final = explode("|",$re);
		Mage::getModel('creditapp/creditmodel')->updateAfterWebCustomerApplication($final, $_POST['appid']);
		

		Mage::getSingleton('core/session')->setFinal($final);
		$this->_redirect('*/*/index');
		
	}
	
	public function step2PostAction(){
		$s = '1';
		if(isset($_POST['qsetid'])){
			
			$result = Mage::helper('creditapp')->Auth_Continue($_POST);
			
			
			$re = $result->Auth_ContinueResult;
			
			
			
			if($re->Result=='PASSED'){
				Mage::getModel('creditapp/creditmodel')->updateAfterAuthContinueResult($_POST['appid'],Mage::getSingleton('core/session')->getTrackid());
				
					
			}
			
		}
		Mage::getSingleton('core/session')->setStep("2");
		$this->_redirect('*/*/index');
	}
	
	public function step1PostAction(){
		if(Mage::getSingleton('core/session')->getTrackid()){
			Mage::getModel('creditapp/creditmodel')->markFirstPageCompleted(Mage::getSingleton('core/session')->getTrackid());
			
		}
		
		if( isset($_POST['psswd']) || isset($_POST['psswd2']) ){
			$fname		= $_POST['fname'];
			$lname		= $_POST['lname'];
			$email		= $_POST['emailid'];
			$street		= $_POST['street1'];
			$street2	= $_POST['street2'];
			$city		= $_POST['city'];
			$region		= $_POST['state'];
			$postcode	= $_POST['postcode'];
			
			$psswd = $_POST['psswd'];
			
			$customer = Mage::getModel('customer/customer');
			$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
			$customer->loadByEmail($email);
			
			// -----------------------------------------------------------------------------------------------
			// Check if the email exist on the system.
			// -----------------------------------------------------------------------------------------------
			if(!$customer->getId()) {
				$customer->setEmail($email); 			//set user data
				$customer->setFirstname($fname);
				$customer->setLastname($lname);
				$customer->setPassword($psswd);
			
				try {
				  $customer->save();
				  $customer->setConfirmation(null);
				  $customer->save(); 
				  $customer->sendNewAccountEmail();
				} catch(Exception $ex){ }			
			}
		} 

		$state = '';

		if(isset($_POST['id_type'])){
			if($_POST['id_type']=='AU1'||$_POST['id_type']=='AU2'){

				$state = 'CA';
			} else {
				$state = $_POST['id_state'];
			}
		} elseif (isset($_POST['id_state'])){
			$state = $_POST['id_state'];
		} else {
			$state = 'CA';		//default case
		}

		try {
		    $result = Mage::helper('creditapp')->Auth_Init($_POST);
		    $re = $result->Auth_InitResult;	
		} catch(Exception $ex){
		    $re = 0;
		    
		}
			//this is throwing an error
		
		Mage::getSingleton('core/session')->setStep("1");
		Mage::getSingleton('core/session')->setRe($re);
		Mage::getSingleton('core/session')->setFname($_POST['fname']);
		Mage::getSingleton('core/session')->setLname($_POST['lname']);
		Mage::getSingleton('core/session')->setEmail($email);
		Mage::getSingleton('core/session')->setPassword($psswd);
		Mage::getSingleton('core/session')->setConfirmpass($psswd2);
		$this->_redirect('*/*/index');
	}
	
	public function validateAction(){
		
		$credit = Mage::helper('creditapp')->ValidateAddress($_POST);
		if($credit->ValidateAddressResult == 1){
			Mage::getModel('creditapp/creditmodel')->markValidated($_POST['appid']);
		}
		echo $credit->ValidateAddressResult;
		
	}
	
	public function creditAction(){
		echo Mage::getModel('creditapp/creditmodel')->saveOrUpdateCreditApp($_POST, $_SERVER);
	}
	
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Credit Application"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("credit application", array(
                "label" => $this->__("Credit Application"),
                "title" => $this->__("Credit Application")
		   ));

      $this->renderLayout(); 
	  
    }
    
    
}
