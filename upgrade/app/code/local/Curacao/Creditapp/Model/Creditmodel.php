<?php

class Curacao_Creditapp_Model_Creditmodel extends Mage_Core_Model_Abstract
{

      protected function _construct()
      {
	  $this->_init('creditapp/creditmodel');
      }
      
      public function markValidated($id){
	  $connection = Mage::getSingleton('core/resource')->getConnection('core_write');	
	  $sql = "UPDATE `credit_app` SET `is_validate_address_complete` = '1'  WHERE `credit_id` = ".$id;
	  $connection->query($sql);
      }
      
      public function markFirstPageCompleted($trackId){
	  $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
	  $sql = "update userActivitytrack set complete_first_page = 1 where uaId = ".$trackId;
	  $connection->query($sql);
      }
      
      public function loadByAppId($appid){
	      $read= Mage::getSingleton('core/resource')->getConnection('core_read');
		
		$q = 'select * from credit_app where `credit_id` = '.$appid;
		$value = $read->query($q);
		$customer = $value->fetch();
		return $customer;
      }
      
      public function updateAfterAuthContinueResult($appid,$trackId)
      {
	     $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
	     $sql = "UPDATE `credit_app` SET `is_lexis_nexus_complete` = '1'  WHERE `credit_id` = ".$appid;
		$connection->query($sql);
		$q = "update userActivitytrack set pass_auth = 1 where uaId = ".$trackId;
		$connection->query($q);
      }
      
      public function updateCreditAppAndActivity($_POST){
	  $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
		if(Mage::getSingleton('core/session')->getTrackid()){
			$sql = "update userActivitytrack set finishapp = 1 where uaId = ".Mage::getSingleton('core/session')->getTrackid();
			$connection->query($sql);
		}
	  $sql = "UPDATE `credit_app` SET `company` = '".$_POST['company']."', 
										`work_phone` = '".$_POST['wphone']."', 
										`maiden_name` = '".$_POST['maidenname']."', 
										`work_year` = '".$_POST['eyears']."', 
										`work_month` = '".$_POST['emonths']."', 
										`salary` = '".$_POST['salary']."'
										 WHERE `credit_id` = ".$_POST['appid'];		
		$connection->query($sql);
						
      }
      
      public function updateAfterWebCustomerApplication($final, $appid){
	      $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
	
		if($final[0]=='APPROVED'){
			if(Mage::getSingleton('core/session')->getTrackid()){
				$sql = "update userActivitytrack set approve = 1 where uaId = ".Mage::getSingleton('core/session')->getTrackid();
				$connection->query($sql);
			}
			$query = "UPDATE `credit_app` SET `is_web_customer_application_complete` = '1'  WHERE `credit_id` = ".$appid;
			$connection->query($query);
			
		}elseif($final[0]=='PENDING'){
			$query = "UPDATE `credit_app` SET `is_web_customer_application_complete` = '2'  WHERE `credit_id` = ".$appid;
			
			if(Mage::getSingleton('core/session')->getTrackid()){
				$sql = "update userActivitytrack set pending_app = 1 where uaId = ".Mage::getSingleton('core/session')->getTrackid();
				$connection->query($sql);
			}
			
		}else{
			$sql = "update userActivitytrack set decline = 1 where uaId = ".Mage::getSingleton('core/session')->getTrackid();
			$connection->query($sql);
		}
      
      }
      
      
      public function saveOrUpdateCreditApp($_POST, $_SERVER){
		$state = '';
		if($_POST['id_type']=='AU1'||$_POST['id_type']=='AU2'){
			$state = 'CA';
		}else{
			$state = $_POST['id_state'];
		}
		$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
		if($_POST['appid']==0){
			$sql = "INSERT INTO `credit_app` (`firstname`, `lastname`, `email_address`, `telephone`, `address1`, `address2`, `city`, `state`, `zipcode`, `res_year`, `res_month`, `telephone2`, `ssn`, `dob`, `id_num`, `id_type`, `id_country`, `id_state`, `id_expire`,  `maiden_name`, `company`, `work_phone`, `work_year`, `work_month`, `applied_date`, `ip_address`, `salary`, `aggp`, `is_lexis_nexus_complete`, `is_validate_address_complete`, `is_web_customer_application_complete`, `is_registration_complete`, `language`) VALUES ('".$_POST['fname']."', '".$_POST['lname']."','".$_POST['emailid']."', '".$_POST['area'].$_POST['local1'].$_POST['local2']."', '".$_POST['street1']."', '".$_POST['street2']."', '".$_POST['city']."', '".$_POST['state']."', '".$_POST['postcode']."', '".$_POST['years']."', '".$_POST['months']."', '".$_POST['cphone1'].$_POST['cphone2'].$_POST['cphone3']."', '".$_POST['ssn1'].$_POST['ssn2'].$_POST['ssn3']."', '".$_POST['dobY'].'-'.$_POST['dobM'].'-'.$_POST['dobD']."', '".$_POST['id_number']."', '".$_POST[
'id_type']."', 'USA', '".$state."','".$_POST['idexpY'].'-'.$_POST['idexpM'].'-'.$_POST['idexpD']."', '', '', '', '', '', '".date('Y-m-d')."', '".$_SERVER['REMOTE_ADDR']."', '', '0', '0', '0', '0', '0', '')";
			$connection->query($sql);
			$lastInsertId = $connection->lastInsertId();  
			return $lastInsertId;  
		}else{
			$sql = "UPDATE `credit_app` SET `firstname` = '".$_POST['fname']."', 
													  `lastname` = '".$_POST['lname']."', 
													  `telephone` = '".$_POST['area'].$_POST['local1'].$_POST['local2']."', 
													  `address1` = '".$_POST['street1']."', 
													  `address2` = '".$_POST['street2']."', 
													  `email_address` = '".$_POST['emailid']."',
													  `city` = '".$_POST['city']."', 
													  `state` = '".$_POST['state']."', 
													  `zipcode` = '".$_POST['postcode']."', 
													  `res_year` = '".$_POST['years']."', 
													  `res_month` = '".$_POST['months']."', 
													  `telephone2` = '".$_POST['cphone1'].$_POST['cphone2'].$_POST['cphone3']."', 
													  `ssn` = '".$_POST['ssn1'].$_POST['ssn2'].$_POST['ssn3']."', 
													  `dob` = '".$_POST['dobY'].'-'.$_POST['dobM'].'-'.$_POST['dobD']."', 
													  `id_num` = '".$_POST['id_number']."', 
													  `id_type` = '".$_POST['id_type']."', 
													  `id_expire` = '".$_POST['idexpY'].'-'.$_POST['idexpM'].'-'.$_POST['idexpD']."', 
													  `id_state` = '".$state."'
													  WHERE `credit_id` = ".$_POST['appid'];
		
			$connection->query($sql);
			return $_POST['appid'];
		}
      }
      
	public function insertGwmtracking($hid, $sub_id, $aff_id,$sid ){
		$connection = Mage::getSingleton('core/resource')->getConnection('core_write');
		$gwm = "insert into gwmtracking (hid,subid,affid, created_date, sid) values('".$hid."','".$sub_id."','".$aff_id."', '".date('Y-m-d')."', '".$sid."' )";
		$connection->query($gwm);
		$lastInsertId = $connection->lastInsertId();
		return $lastInsertId;
	}
	
	public function updateEmailCampaign($utm_campaign, $current_page){
		$read= Mage::getSingleton('core/resource')->getConnection('core_read');
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
						
		$q = "select * from emailcampaign where campaignId = '".$_GET['utm_campaign']."'";
		$resu = $read->fetchAll($q); 
		
		if(count($resu)>0){
			foreach ($resu as $ro) {
				$click = (int)$ro['clicks'];
				$click++;
				$sq = "update emailcampaign set clicks = ".$click." where ecId = ".$ro['ecId'];
				$write->query($sq);
				
						
				if($current_page == 'checkout/cart/'){
					$cart = Mage::helper('checkout/cart')->getCart()->getItemsCount();
					$total = Mage::helper('checkout/cart')->getQuote()->getGrandTotal();
					$total = $total + $ro['amount'];
					$shop = (int)$ro['shop'];
					$shop++;
					$sq = "update emailcampaign set shop = ".$shop.", amount = '".$total."' where ecId = ".$ro['ecId'];
					$write->query($sq);
					
				}
				if($current_page=='onestepcheckout/'){
					$checkout = (int)$ro['checkout'];
					$checkout++;
					$sq = "update emailcampaign set checkout = ".$checkout." where ecId = ".$ro['ecId'];
					$write->query($sq);
				 }
			}
			
		
		}else{
			$q = "insert into emailcampaign (campaignId, clicks) values ('".$utm_campaign."',1)";
			$write->query($q);
		}
	}
	
	public function updateEmailCampaignFromSession($current_page){
		$read= Mage::getSingleton('core/resource')->getConnection('core_read');
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$q = "select * from emailcampaign where campaignId = '".Mage::getSingleton('core/session')->getCampaignid()."'";
		$resu = $read->fetchAll($q);
		if(count($resu)>0){
			foreach ($resu as $ro) {
				if($current_page == 'checkout/cart/'){
					$cart = Mage::helper('checkout/cart')->getCart()->getItemsCount();
					$total = Mage::helper('checkout/cart')->getQuote()->getGrandTotal();
					
					$total = $total + $ro['amount'];
					$shop = (int)$ro['shop'];
					$shop++;
					$sq = "update emailcampaign set shop = ".$shop.", amount = '".$total."' where ecId = ".$ro['ecId'];
					$write->query($sq);
					
					
				}
			  if($current_page=='onestepcheckout/'){
				$checkout = (int)$ro['checkout'];
				$checkout++;
				$sq = "update emailcampaign set checkout = ".$checkout." where ecId = ".$ro['ecId'];
				$write->query($sq);
			  }
			}
			
		}
	}
	
	public function runQuery($query){
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$write->query($query);
		$lastInsertId = $write->lastInsertId();
		return $lastInsertId;
	}
	
	public function updateUserActivity($current_page, $_COOKIE){
		$read= Mage::getSingleton('core/resource')->getConnection('core_read');
		$write = Mage::getSingleton('core/resource')->getConnection('core_write');
		$q = 'select * from userActivitytrack where uaId = '.Mage::getSingleton('core/session')->getTrackid();
		$resu = $read->fetchAll($q);
		if(count($resu)>0){
			foreach ($resu as $row) {
				$p = $row['path'].','.$current_page;
				$sql = 'update userActivitytrack set path = "'.$p.'" where uaId = '.$row['uaId'];
				$write->query($sql);
				if($current_page == 'checkout/cart/'){
					$cart = Mage::helper('checkout/cart')->getCart()->getItemsCount();
					$total = Mage::helper('checkout/cart')->getQuote()->getGrandTotal();
					$pretotal = $total;
					if($row['finishcheckout']=='1'){
						$total = $total + $row['amount'];
					}
					
					
					if($cart>0){
						$sql = "update userActivitytrack set shop = 1, amount = '".$total."' where uaId = ".Mage::getSingleton('core/session')->getTrackid();
						$write->query($sql);
						$paid = 0;
						if(isset($_COOKIE['preapprove_promo_id'])){
							$paid = $_COOKIE['preapprove_promo_id'];
						}else{
							$paid = Mage::getSingleton('core/session')->getPreapproveid();
						}
						if($paid > 0){
							$q = "update preapproved set shop = '1', amount = '".$pretotal."' where paID = ".$paid;
							$write->query($q);
						}
					}
					
					
				}
			  if($current_page=='onestepcheckout/'){
				$sql = "update userActivitytrack set checkout = 1, shop = 1 where uaId = ".Mage::getSingleton('core/session')->getTrackid();
				$write->query($sql);
				
				$paid = 0;
					if(isset($_COOKIE['preapprove_promo_id'])){
						$paid = $_COOKIE['preapprove_promo_id'];
					}else{
						$paid = Mage::getSingleton('core/session')->getPreapproveid();
					}
					if($paid > 0){
						$q = "update preapproved set checkout = '1' where paID = ".$paid;
						$write->query($q);
					}	
			  }
			}
			
		}
		$session = Mage::getSingleton('customer/session', array('name'=>'frontend')); 

		if($session->isLoggedIn()){
			$sql = "update userActivitytrack set userloggedin = 1 where uaId = ".Mage::getSingleton('core/session')->getTrackid();
			$write->query($sql);
		}
	
	}

	public function createUserActivity($params, $server, $current_page)
	{


		if(isset($params['mintrack'])){
			$string = $server["HTTP_REFERER"];	
			$parse = explode('?',$string);
		
			if(isset($params['keyword'])){
		
				if (strpos($parse[0],'google.com') !== false) {
					$more = explode('&',$parse[1]);
					$key = '';
					for($i=0;$i<sizeof($more);$i++){
						if(substr($more[$i],0,2)=='q='){
							$key = $more[$i];
						}
					}
					$mintrack = $params['mintrack'];
			
					if(isset($params['hid'])){
						$mintrack = 'GWM';
					}	
			
					$query = "insert into userActivitytrack(browser,ip,path,created_date,campaign,keyword,googleKeyword)values('".$server['HTTP_USER_AGENT']."','".$server['REMOTE_ADDR']."','".$current_page."', '".date('Y-m-d')."', '".$mintrack."','".$params['keyword']."','".$key."')";	
		
		   
				}else{
	
		
					$query = "insert into userActivitytrack(browser,ip,path,created_date,campaign,keyword)values('".$server['HTTP_USER_AGENT']."','".$server['REMOTE_ADDR']."','".$current_page."', '".date('Y-m-d')."', '".$params['mintrack']."','".$params['keyword']."')";
				}
			}else{
		
				if (strpos($parse[0],'google.com') !== false) {
					$more = explode('&',$parse[1]);
					$key = '';
					for($i=0;$i<sizeof($more);$i++){
							if(substr($more[$i],0,2)=='q='){
								$key = $more[$i];
							}
					}
			
					$query = "insert into userActivitytrack(browser,ip,path,created_date,campaign,googleKeyword)values('".$server['HTTP_USER_AGENT']."','".$server['REMOTE_ADDR']."','".$current_page."', '".date('Y-m-d')."', '".$params['mintrack']."','".$key."')";	
		   
				}else{
		
					$query = "insert into userActivitytrack(browser,ip,path,created_date,campaign)values('".$server['HTTP_USER_AGENT']."','".$server['REMOTE_ADDR']."','".$current_page."', '".date('Y-m-d')."', '".$params['mintrack']."')";
				}
			}
		}else{
			$query = "insert into userActivitytrack(browser,ip,path,created_date)values('".$server['HTTP_USER_AGENT']."','".$server['REMOTE_ADDR']."','".$current_page."', '".date('Y-m-d')."')";
	}
	
	return $this->runQuery($query);
	
	}
}


?>
