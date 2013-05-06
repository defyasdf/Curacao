<?php include_once('_includes/dictionary_eng.php');?>
<?php include_once('_includes/mage_inc.php'); ?>
<?php

	$_error = '';


	Mage::getSingleton('core/session', array('name' => 'frontend'));
	$customer = Mage::getModel('customer/customer');
	$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
	
	if(  Mage::getSingleton('customer/session')->isLoggedIn() ){
		$_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB). 'credit/creditapp.php';
		header("Location: ".$_url);	
	}

	if( isset($_POST['id_onestepcheckout_username']) || isset($_POST['onestepcheckout_password']) || isset($_POST['emailid']) ){

		if( isset($_POST['emailid']) ){
			$_email = $_POST['emailid'];					
			$customer = Mage::getModel('customer/customer');
			$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
			
			$customer->loadByEmail($_email);
			$c_id = $customer->getId();

			//verify that user email address does not exist			
			if( ! $customer->getId() ){
				$_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB). 'credit/creditapp.php?email='.$_POST['emailid'];
				header("Location: ".$_url);
			} else {
				$_error = "The email address you entered is being used, please login.";
			}
		
		} else if ( isset($_POST['id_onestepcheckout_username']) && isset($_POST['onestepcheckout_password']) ) {
			$_email = $_POST['id_onestepcheckout_username'];
			$_psswd = $_POST['onestepcheckout_password'];

			$customer = Mage::getModel('customer/customer');
			$customer->setWebsiteId(Mage::app()->getWebsite()->getId());
			
			$customer->loadByEmail($_email);
			$customer_id = $customer->getId();
				
						
			if( $customer_id > 0 ){

			}
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Credit Application: Login/Register</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/base.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/skeleton.css" media="all" />
		<link rel="stylesheet" type="text/css" href="/skin/frontend/enterprise/curacao-responsive/css/styles.css" media="all" />
	</head>
	<body>
		<div class="container small">
			<h2><?php echo $LOGIN_TITLE ?></h2>
			<div class="error"><p><?php echo($_error) ?></p></div>
			<div class="messages"><p><?php echo $LOGIN_CALLOUT ?></p></div>
								
			<div class="col2-set">
				<div class="col ten columns alpha">
				    <fieldset>
						<form id="login_form">
							<input type="hidden" id="appId" value="0" name="appid" />
				    		<h2>Existing Customers:</h2>
				             <div class="field">
				                <label for="id_onestepcheckout_username"><?php echo EMAIL;?><span id="require">*</span></label>
								<input type="text" id="onestepcheckout_username" name="onestepcheckout_username" class="input-text" tabindex="100">
				            </div>
				            <div class="field">
				                <label for="psswd"><?php echo $PASSWORD_LABEL ?><span id="require">*</span></label>
								<input type="password" id="onestepcheckout_password" class="input-text" name="onestepcheckout_password" tabindex="101">
				            </div>
				            <div class="field">
						    	<button id="login-button" class="button" onClick="login_handler(); return false;">Sign In &gt;</button>
						    	<div class="clearfix"></div>
				                <p>Forgot Your Password? <a href="/customer/account/forgotpassword" id="forgot-password-link">Click Here</a></p>
				            </div>
						</form>
			    	</div>
			    	<div class="col ten columns omega">
				    <fieldset>
						<form action="creditapp_login_register.php" method="post" id="newappform">
				    		<h2>New Applicants:</h2>
				             <div class="field">
								<label for="emailid"><?php echo EMAIL;?><span id="require">*</span></label>
								<input type="text" name="emailid" required class="required" id="emailid" />
				            </div>
				            <div class="field">
						    	<button class="button" onClick="new_account();">Next &gt;</button>
				            </div>
						</form>
					</fieldset>
		    	</div>
			</div>
		</div>
		<script src="/js/jquery/jquery-1.7.2.min.js" type="text/javascript"></script>
		<script>

        var mode = 'login';

        var forgot_password_link = jQuery('#forgot-password-link');
        
        var forgot_password_container = jQuery('#login-popup-contents-forgot');
        var forgot_password_loading = jQuery('#forgot-loading');
        var forgot_password_error = jQuery('#forgot-error');
        var forgot_password_success = jQuery('#forgot-success');
        var forgot_password_button = jQuery('#forgot-button');

        var login_link = jQuery('#return-login-link');				//
        var login_error = jQuery('#login-error');					//
        var login_loading = jQuery('#login-loading');				//
        var login_button = jQuery('#login-button');
        var login_form = jQuery('#login_form');
        var login_username = jQuery('#email');
        
        
        var login_url = '<?php echo(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB)); ?>onestepcheckout/ajax/login/';
        
		//login-form
		function frameResize(){
		}
		
		function new_account(){
            //var parameters = newappform.serialize(true);
           // var url = options.login_url;
            
			//jQuery('#emailid').value();
		
		}
		
		login_handler = function(e) {

            var parameters = login_form.serialize(true);
			var username = jQuery('#onestepcheckout_username').val();
			var password = jQuery('#onestepcheckout_password').val();
			
			jQuery.ajax({
				type: "POST",
				url: login_url,
				data: { appid: '0', onestepcheckout_username: username, onestepcheckout_password: password},
				success: function(){
					document.location = './creditapp.php';
				}
			}).done(function( msg ) {

				if(msg["success"] == true){
					document.location = './creditapp.php';
				} else {
					alert( msg["error"] );
				}
			});
        };

        forgot_password_handler = function(e) {
            var email = jQuery('#id_onestepcheckout_email').getValue();

            if(email == '') {
                alert(options.translations.invalid_email);
                return;
            }

            showForgotPasswordLoading();

            /* Prepare AJAX call */
            var url = options.forgot_password_url;

            new Ajax.Request(url, {
                method: 'post',
                parameters: { email: email },
                onSuccess: function(transport) {
                    var result = transport.responseText.evalJSON();

                    if(result.success) {
                        /* Show success message */
                        showForgotPasswordSuccess();

                        /* Pre-set username to simplify login */
                        login_username.setValue(email);
                    } else {
                        /* Show error message */
                        showForgotPasswordError();
                    }

                }.bind(this)
            });
        };
        
        
		function showLoginError(error) {
	        login_error.show();
	        login_loading.hide();
	
	        if(error) {
	            login_error.update(error);
	        }
	    }
	
	    function showLoginLoading() {
	        login_loading.show();
	        login_error.hide();
	    }
	
	    function showForgotPasswordSuccess() {
	        forgot_password_error.hide();
	        forgot_password_loading.hide();
	        forgot_password_success.show();
	    }
	
	    function showForgotPasswordError() {
	        forgot_password_error.show();
	        forgot_password_error.update(options.translations.email_not_found);
	        forgot_password_loading.hide();
	    }
	
	    function showForgotPasswordLoading() {
	        forgot_password_loading.show();
	        forgot_password_error.hide();
	    }
        
		</script>
	</body>
</html>



