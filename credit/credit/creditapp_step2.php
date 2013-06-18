<?php include_once('_includes/dictionary_eng.php');?>
<?php include_once('_includes/mage_inc.php'); ?>
<?php

	//if we are creating an account…
	if( isset($_POST['psswd']) || isset($_POST['psswd2']) ){
		$fname		= $_POST['fname'];
		$lname		= $_POST['lname'];
		$email		= $_POST['emailid'];
		$street		= $_POST['add1'];
		$street2	= $_POST['add2'];
		$city		= $_POST['city'];
		$region		= $_POST['state'];
		$postcode	= $_POST['zip'];
		
		$psswd = $_POST['psswd'];
		
		$customer = Mage::getModel('customer/customer');
		$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
		$customer->loadByEmail($email);
		//
		// Check if the email exist on the system.
		// If YES,  it will not create a user account. 
		//		
		if(!$customer->getId()) {
		   //setting data such as email, firstname, lastname, and password 
		
		  $customer->setEmail($email); 
		  $customer->setFirstname($fname);
		  $customer->setLastname($lname);
		  $customer->setPassword($psswd);
		}
		
		try{
		  //the save the data and send the new account email.
		  $customer->save();
		  $customer->setConfirmation(null);
		  $customer->save(); 
		  $customer->sendNewAccountEmail();
		}
		
		catch(Exception $ex){
		 
		}

	}
	
	ini_set('max_execution_time', 300);
	ini_set('display_errors', 1);

	$state = '';


	if(isset($_POST['id_type'])){
		if($_POST['id_type']=='AU1'||$_POST['id_type']=='AU2'){
			$state = 'CA';
		}else{
			$state = $_POST['id_state'];
		}
	} elseif (isset($_POST['id_state'])){
		$state = $_POST['id_state'];	
	} else {
		$state = 'CA';
	}

	$proxy = new SoapClient('https://exchangeweb.lacuracao.com:2007/ws1/lacauth/service.asmx?WSDL');

	$result = $proxy->Auth_Init(array('Params' => 
									array(	'Firstname' =>$_POST['fname'],
											'Middlename'=>'',
											'Lastname'=>$_POST['lname'],
											'HomePhone'=>$_POST['area'].$_POST['local1'].$_POST['local2'],
											'Ssn'=>$_POST['ssn1'].$_POST['ssn2'].$_POST['ssn3'],
											'ID_no'=>$_POST['id_number'],
											'ID_State'=>$state,
											'DOB_Month'=>$_POST['dobM'],
											'DOB_Day'=>$_POST['dobD'],
											'DOB_Year'=>$_POST['dobY'],
											'Street'=>$_POST['add1'].' '.$_POST['add2'],
											'City'=>$_POST['city'],
											'State'=>$_POST['state'],
											'Zip'=>$_POST['zip'],
											'AcctTransId'=>$_POST['appid'],
											'Language'=>'English',
		                                    'TimeOut' => 0
										)
                          			),
			    	                "http://www.LaCuracao.net/LACmis/webservice",
				                    "http://www.LaCuracao.net/LACmis/webservice/Auth_Init", 
			                    	false, null, 'rpc', 'literal'
                    			);
	$re = $result->Auth_InitResult;
?>
<html>
	<head>
		<title>Credit Application</title>		
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/base.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/skeleton.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/styles.css" media="all" />
	</head>
	<body>
		<div class="container small">
	    	<div class="col twenty columns">
<?php if($re->Result=='QUESTIONS'){ ?>
			    <h2><?php echo DATAVERIFICATION?></h2>
			    <fieldset>
				    <form action="lexusstep1.php" method="post" id="lexusform">
				    	<input type="hidden" id="appId" value="<?php echo $_POST['appid']?>" name="appid" />
				    	<input type="hidden" value="<?php echo $re->QuestionSetId?>" name="qsetid" />
				        <input type="hidden" value="<?php echo $re->QuestionId?>" name="qid" />
				        <input type="hidden" value="<?php echo $re->TransactionNumber?>" name="tnum" />
				        <input type="hidden" value="<?php echo $re->TransactionId?>" name="tid" />
				        
				        <div>Help Text : <?php echo $re->HelpText?></div>
				        
				        <p>Question: <?php echo $re->Question?></p>
				        
				    	<div>
				        <?php 
							$choice = $re->Choice->aChoice;
							//print_r($choice);
							for($i=0;$i<sizeof($choice);$i++){ 
						?>
							<p><input type="radio" value="<?php echo $choice[$i]->ChoiceId?>" name="choiceid" /><?php echo $choice[$i]->Choice?></p>
						<?php
							} 
						?>
				        </div>
    		           <div class="clearfix"></div>
				    	<button class="button" onClick="return formvalidation()">Next &gt;</button>
                        <span style="display:none" class="please-wait" id="please-wait">
				        <img class="v-middle" alt="" src="http://data.icuracao.com/skin/frontend/enterprise/curacao-responsive/images/opc-ajax-loader.gif"> Loading next step...    </span>
                        
				    </form>
			    </fieldset>
        
<? } else { ?>
			    <h2 style="color:#FF0000">Lexus Nexus authentication failed.</h2>
			    <h2><?php echo THANKS?></h2>
			    <br />
				<p><?php echo DECLINE?>
				</p><br />
				<p><?php echo CUSTSERVICE?></p>
<?php } ?>		    
			</div>
		</div>
	</body>
</html>

<script src="/js/jquery/jquery-1.7.2.min.js" type="text/javascript"></script>
		<script type="text/javascript">
		
			function formvalidation(){
				if (!jQuery("input[name='choiceid']:checked").val()) {
				   alert('Please Choose an answer from the list!');
				   return false;
				}
				else {
					jQuery("#please-wait").show(); 
					jQuery("#lexusform").submit();
				}
				
				
				
				
			}
			</script>