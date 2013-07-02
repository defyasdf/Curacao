<?php
ini_set('display_errors', 1);
/**
 * Created by JetBrains PhpStorm.
 * User: sanjayprajapati
 * Date: 6/17/13
 * Time: 7:38 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<!--Login Popup-->

<div class="account-login" id="login_content_top">
    	<div class="onestepcheckout-error" id="onestepcheckout-login-error-link" style="display:none;"></div>
        <div class="eight columns omega registered-users" id="login-container">
            <form action="" onsubmit="return create_login_link()">
            <div class="content" id="login-content">
                <ul class="form-list" style="border:none !important;">
                <h2 class="legend">Log Into your account</h2>

                   <div id="ajax_login_form">
                    <p>If you have an account with us, log in using your email address.</p>
                    <li>
                        <label for="email" class="required">Email Address<em>*</em></label>
                        <div class="input-box">
                            <?php
								//print_r( $_SESSION['persistent_shopping_cart'] );


                            if(isset($_COOKIE['EmailCustomer'])){?>
                                <input name="login[username]" value="<?php echo $_COOKIE['EmailCustomer'];?>" id="useremail_link" class="input-text required-entry validate-email" title="Email Address" type="text">
                            <?php }else{?>
                                <input name="login[username]" value="" id="useremail_link" class="input-text required-entry validate-email" title="Email Address" type="text">
                            <?php } ?>
</div>
</li>
<li>
    <label for="pass" class="required">Password<em>*</em></label>
    <div class="input-box">
        <input name="login[password]" class="input-text required-entry validate-password" id="userpass_link" title="Password" type="password">
    </div>
</li>

<li id="remember-me-box" class="control">
    <div class="input-box">
        <input name="persistent_remember_me" class="checkbox checkbox-label" id="remember_meZwnY9znjWf" checked="checked" title="Remember Me" type="checkbox">
    </div>
    <label for="remember_meZwnY9znjWf" class="checkbox-label">Remember Me</label>
    <!-- <a class="link-tip" href="#" class="remember">What's this?</a> -->
</li>



<p class="clear"></p>
<p class="form-required"><em>*</em> Required Fields</p>

<button type="submit" class="button" title="Login" name="send" id="send2_top" onclick="return create_login_link()"><span><span>Login</span></span></button>
</div>
<div id="login-loading_link" style="display:none"><img src="/images/gray_fade_small.gif"><br />
    <p>Logging In .......</p>

</div>
</ul>
<div class="clear"></div>
<p style="margin-left:25px;">Not Registered? <a href="/customer/account/create/" class="f-left">Create an account</a></p>
</div>
</form>
</div>

</div>

<style>
    .customer-account-create .form-list p, .account-login .form-list p{
       margin-bottom:0;
    }

</style>
<!--end Login Pop up-->