<?php 
	include 'includes/config.php';
	$sql = 'select * from users where user_id = '.$_GET['uId'];
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
    $ac = explode(',',$row['access_level']);
?>

<article class="module width_full">
			<header><h3>New User</h3></header>
				
                <div id="notify"></div>
            
            	<div class="module_content">
                <form action="" onsubmit="return updateuser('<?php echo $row['user_id']?>')">
						<fieldset>
							<label>First Name</label>
							<input type="text" name="fName" id="fName" required="required" value="<?php echo $row['fname']?>">
						</fieldset>
						
                        <fieldset>
							<label>Last Name</label>
							<input type="text" name="lName" id="lName" required="required" value="<?php echo $row['lname']?>">
						</fieldset>
                        <fieldset>
							<label>Email</label>
							<input type="email" name="email" id="email" required="required" value="<?php echo $row['email']?>">
						</fieldset>
                        <fieldset>
							<label>User Name</label>
							<input type="text" name="uName" id="uName" required="required" value="<?php echo $row['username']?>">
						</fieldset>
                        <fieldset>
							<label>Password</label>
							<input type="password" name="pass" id="pass" required="required" value="<?php echo $row['password']?>">
						</fieldset>
                        <fieldset>
							<label>Access Level</label>
							<input type="checkbox" name="aLevel" id="aLevel" value="1" <?php if(in_array('1',$ac)){?> checked<?php }?>> Upload Data <input value="2" type="checkbox" name="aLevel" <?php if(in_array('2',$ac)){?> checked<?php }?> id="aLevel"> Search Data <input value="3" <?php if(in_array('3',$ac)){?> checked<?php }?> type="checkbox" name="aLevel" id="aLevel"> Process Data <input value="4" <?php if(in_array('4',$ac)){?> checked<?php }?> type="checkbox" name="aLevel" id="aLevel"> Translate Data <input value="5" <?php if(in_array('5',$ac)){?> checked<?php }?> type="checkbox" name="aLevel" id="aLevel"> Push Data to magento<input value="6" <?php if(in_array('6',$ac)){?> checked<?php }?> type="checkbox" name="aLevel" id="aLevel"> General Admin
						</fieldset>
                      
                        <footer>
                        <div class="submit_link">
                           
                            <input type="submit" value="Submit" class="alt_btn">
                            <input type="submit" value="Reset">
                        </div>
                    </footer>
                    
                    </form>
                        <!--<fieldset style="width:48%; float:left; margin-right: 3%;">  to make two field float next to one another, adjust values accordingly 
							<label>Category</label>
							<select style="width:92%;">
								<option>Articles</option>
								<option>Tutorials</option>
								<option>Freebies</option>
							</select>
						</fieldset>-->
						<!--<fieldset style="width:48%; float:left;">  to make two field float next to one another, adjust values accordingly
							<label>Tags</label>
							<input type="text" style="width:92%;">
						</fieldset><div class="clear"></div> -->
				</div>
			
		</article><!-- end of post new article -->