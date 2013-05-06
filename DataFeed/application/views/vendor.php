<section id="main" class="column"> 
<article class="module width_full">
			<header><h3>New User</h3></header>
                <div id="notify"></div>
            
            	<div class="module_content">
                <form action="index.php/insertvendor" method="post">
                	<input type="hidden" id="err" />
						<fieldset>
							<label>Vendor Name</label>
							<input type="text" name="vName" id="fName" required="required">
						</fieldset>
                        <fieldset>
							<label>User Name</label>
							<input type="text" name="uName" id="uName" required="required" onchange="return chkuser()">
                            <br />
							<div id="chkUser" style="clear:both"></div>
						</fieldset>
                        <fieldset>
							<label>Password</label>
							<input type="password" name="pass" id="pass" required="required">
						</fieldset>
        				<fieldset>
							<label>Vendor ID</label>
							<input type="text" name="vId" id="vId" required="required">
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
        
 </section>