	<section id="main" class="column">
        <article class="module width_full">
			<header><h3>Final Product</h3></header>
				<div class="module_content">
                    <form action="index.php/englishready" method="post">
                   
						<fieldset>
							<label>Product Name</label>
							<input type="text" name="pName" id="pName" required  value="<?php echo htmlspecialchars($_POST['finalpname'],ENT_QUOTES)?>">
						</fieldset>
						<fieldset>
                        	
							<label>Description</label>
							<textarea rows="12" name="pDesc" id="pDesc" required >
                            	<?php echo $_POST['finalpdesc']?>
                            </textarea>
						</fieldset>
						
                        <fieldset>
							<label>Product SKU</label>
							<input type="text" name="pSku" id="pSku" required readonly value="<?php echo $_POST['sku']?>">
						</fieldset>
                        <fieldset>
                        	<?php 
								$sql = "select product_upc from masterproducttable where product_sku = '".$_POST['sku']."'";
								$re = mysql_query($sql);
								$row = mysql_fetch_array($re);
							?>
							<label>Product UPC</label>
							<input type="text" name="pupc" id="pupc" readonly="readonly" value="<?php echo $row['product_upc']?>">
						</fieldset>
                       <!-- <fieldset>
                       		<label>Product Cost</label>
							<input type="text" name="pCost" id="pCost" >
						</fieldset>
                        <fieldset>
                        
							<label>Product Retail</label>
							<input type="text" name="pRetail" id="pRetail" >
						</fieldset>-->
                        <fieldset>
                        
							<label>Product MSRP</label>
							<input type="text" name="pmsrp" id="pmsrp" >
						</fieldset>
                        <fieldset>
                        
                        
							<label>Product MAP</label>
							<input type="text" name="pMAP" id="pMAP">
						</fieldset>
                       <!-- <fieldset>
							<label>Product QTY</label>
							<input type="text" name="pQTY" id="pQTY">
						</fieldset>
                        <fieldset>
							<label>Product Inventory Level</label>
							<input type="text" name="pIlevel" id="pIlevel">
						</fieldset>-->
                        <fieldset>
							<label>Product Brand</label>
							<input type="text" name="pBrand" id="pBrand" value="<?php echo $_POST['brand']?>">
						</fieldset>
                        <fieldset>
							<label>Product Image Path</label>
							<input type="text" name="pImg2" id="pImg2">
                            <input type="button" value="Add to Images" onclick="return addimg()" />
                            </fieldset>
                            <fieldset>
                            <label>Images Preview</label>
                            <table><tr id="imgtabletr">
                            <?php 
																					
											for($i=0;$i<sizeof($_POST['pimg']);$i++){
												
												$img = getimagesize($_POST['pimg'][$i]);
											?>
                                         <td style="margin-right:10px; border-right:2px solid #000;">   
                                        
		                                        <img src="<?php echo $_POST['pimg'][$i]?>" width="60" height="60"><br />
												Width : <?php echo $img[0]?><br />
												Height :<?php echo $img[1]?>
                                                
                                            </td>
											<?php
											
											}
											
										?>
								</tr></table><br />
		           
                            <label>Image Paths</label>
							<textarea name="pimg1" id="pImg1" rows="12">
                            	<?php echo implode(',',$_POST['pimg'])?>
                            </textarea>
                            
						</fieldset>
                      
                        <fieldset>
                        
                        
							<label>Product Features</label>
							<textarea rows="12" name="pFeature" id="pFeature" readonly><?php echo htmlspecialchars_decode($_POST['finalfeatures'])?></textarea>
						</fieldset>
                        
                        <fieldset>
                        
                            
                            
							<label>Product Specification</label>
							<textarea rows="12" name="pSpecs" id="pSpecs" readonly><?php echo htmlspecialchars_decode($_POST['finalspecs'])?></textarea>
						</fieldset>
                        <footer>
                        <div class="submit_link">
                           <input type="button" value="Save" class="alt_btn" onclick="return saveenglish()">
                            <input type="submit" value="English Ready" class="alt_btn">
                           <a href="productwiz.php?sku=<?php echo $_POST['sku']?>"> <input type="button" value="Go Back"></a>
                        </div>
                    </footer>
                    
                    </form>
                    
                    
                </div>
		</article><!-- end of post new article -->
        
		<div class="spacer"></div>
	</section>