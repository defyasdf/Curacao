	<section id="main" class="column">
    	<?php echo $msg ;?>
		<article class="module width_full">
        	
			<header><h3>Login</h3></header>
				<div class="module_content">
	        <form class="post_message" action="index.php/chklogin" method="post">
					<input type="text" value="Username" name="user" onFocus="if(!this._haschanged){this.value=''};this._haschanged=true;"><br>
					<br>

                    <input type="password" value="Password" name="pass" onFocus="if(!this._haschanged){this.value=''};this._haschanged=true;"><br>

					<input type="submit" class="alt_btn" value="Login">
			</form>
            
            </div></article>
        
			</section>

