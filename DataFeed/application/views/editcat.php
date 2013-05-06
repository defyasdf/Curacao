<?php 
	$q = 'select * from categories where id = '.$_GET['catid'];
	$r = mysql_query($q);
	$row = mysql_fetch_array($r);
?>
<section id="main" class="column"> 
<article class="module width_full">
			<header><h3>New User</h3></header>
                <div id="notify"></div>
            
            	<div class="module_content">
                <form action="index.php/updatecategory" method="post">
                	<input type="hidden" name="cId" value="<?php echo $_GET['catid'];?>">
                	<input type="hidden" id="err" />
						<fieldset>
							<label>Category Name</label>
							<input type="text" name="cName" id="fName" required value="<?php echo $row['name']?>">
						</fieldset>
                        <fieldset id="catTree">
							<label>Parent ID</label><br>

							<?php echo $content?>
                            
						</fieldset>
                        
				 		<input type="hidden" name="levels" id="levels" value="0" />	
                      
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