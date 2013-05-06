	<?php 
		$sql = "select * from finalproductlist where product_sku = '".$_GET['sku']."'";
		$re = mysql_query($sql);
		$row = mysql_fetch_array($re);
		
		
		$sp = "select * from spenishdata where product_sku = '".$_GET['sku']."'";
		$re1 = mysql_query($sp);
		$sprow = mysql_fetch_array($re1);
	?>

    <section id="main" class="column">
	
        <article class="module width_half">
			<header><h3>English Copy</h3></header>
             
				<div class="module_content">
                	
                  
						<fieldset>
							<label>Product Name</label>
                            
							<input type="text" name="pName" id="pName" required  value="<?php echo $row['prduct_name']?>"><br />

                            
                            
                            <button onclick="return translateitem('pName')" class="btn btn btn-primary" style="margin-left:15px; margin-bottom:10px;">
                                        <i class="icon-trash icon-white"></i>
                                        <span>Translate</span>
                                    </button>
                            
						</fieldset>
						<fieldset>
                        	
							<label>Description</label><br />

							<textarea rows="12" name="pDesc" id="pDesc" required  style="width:490px;" class="jhtmls">
                            	<?php echo $row['product_description']?>
                            </textarea>
                            
                            <button onclick="return translateitem('pDesc')" class="btn btn btn-primary" style="margin-left:15px; margin-bottom:10px;">
                                        <i class="icon-trash icon-white"></i>
                                        <span>Translate</span>
                                    </button>
                            </fieldset><br />
                            
						</fieldset>
						<!--
                        <fieldset>
							<label>Product SKU</label>
							<input type="text" name="pSku" id="pSku" required readonly value="<?php echo $row['product_sku']?>">
						</fieldset>
                        <fieldset>
                        	
							<label>Product UPC</label>
							<input type="text" name="pupc" id="pupc" readonly value="<?php echo $row['product_upc']?>">
						</fieldset>
                        <fieldset>
                       		<label>Product Cost</label>
							<input type="text" name="pCost" id="pCost" value="<?php echo $row['product_cost']?>" readonly>
						</fieldset>
                        <fieldset>
                        
							<label>Product Retail</label>
							<input type="text" name="pRetail" id="pRetail" value="<?php echo $row['product_retail']?>" readonly>
						</fieldset>
                        <fieldset>
                        
							<label>Product MSRP</label>
							<input type="text" name="pmsrp" id="pmsrp" value="<?php echo $row['product_msrp']?>" readonly>
						</fieldset>
                        <fieldset>
                        
                        
							<label>Product MAP</label>
							<input type="text" name="pMAP" id="pMAP" value="<?php echo $row['product_map']?>" readonly>
						</fieldset>
                        <fieldset>
							<label>Product QTY</label>
							<input type="text" name="pQTY" id="pQTY" value="<?php echo $row['product_qty']?>" readonly>
						</fieldset>
                        <fieldset>
							<label>Product Inventory Level</label>
							<input type="text" name="pIlevel" id="pIlevel" value="<?php echo $row['product_inventory_level']?>" readonly>
						</fieldset>
                        <fieldset>
							<label>Product Brand</label>
							<input type="text" name="pBrand" id="pBrand" value="<?php echo $row['product_brand']?>" readonly>
						</fieldset>-->
                        
                        <fieldset>
                        
                        
							<label>Product Features</label>
							<textarea rows="12" name="pFeature" id="pFeature"  style="width:490px;" class="jhtmls"><?php echo htmlspecialchars_decode($row['product_features'])?></textarea>
                            
                            <button onclick="return translateitem('pFeature')" class="btn btn btn-primary" style="margin-left:15px; margin-bottom:10px;">
                                        <i class="icon-trash icon-white"></i>
                                        <span>Translate</span>
                                    </button>
                            
                            
						</fieldset>
                        
                        <fieldset>
                        
                            
                            
							<label>Product Specification</label>
							<textarea rows="12" name="pSpecs" id="pSpecs"  style="width:490px;" class="jhtmls"><?php echo htmlspecialchars_decode($row['product_specs'])?></textarea>
                            
                            <button onclick="return translateitem('pSpecs')" class="btn btn btn-primary" style="margin-left:15px; margin-bottom:10px;">
                                        <i class="icon-trash icon-white"></i>
                                        <span>Translate</span>
                                    </button>
                            
                            
						</fieldset>
                        
                        <fieldset>
							<label>Product Image Path</label>
							<input type="text" name="pImg2" id="pImg2">
                            <input type="button" value="Add to Images" onclick="return addimg()" />
                            </fieldset>
                            
                         <fieldset>
                            <label>Images Preview</label>
                            <div style="overflow-y:hidden; overflow-x:scroll;">
                            <table><tr id="imgtabletr">
                            <?php 
								$imgs = explode(',',$row['product_img_path']);
																					
											for($i=0;$i<sizeof($imgs);$i++){
												
												$img = getimagesize($imgs[$i]);
											?>
                                         <td style="margin-right:10px; border-right:2px solid #000;">   
                                        
		                                        <img src="<?php echo $imgs[$i]?>" width="60" height="60"><br />
												Width : <?php echo $img[0]?><br />
												Height :<?php echo $img[1]?>
                                                
                                            </td>
											<?php
											
											}
											
										?>
								</tr></table><br />
		           			</div>
                            <label>Image Paths</label><br />

							<input type="text" name="pImg1" id="pImg1" value="<?php echo $row['product_img_path']?>" style="height:200px; width:590px" />
                            <!--<textarea name="pimg1" id="pImg1" rows="12">
                            	
                            </textarea>-->
                            
						</fieldset>
                        
                        
                        <footer>
                        <div class="submit_link">
                        	<input type="button" value="Save" class="alt_btn" onclick="return saveeng()">
                            <a id="various2" href="preview.php?source=english&id=<?php echo $row['fpl_id']?>">
                            <input type="submit" value="Preview" class="alt_btn">
                            </a>
                           
                        </div>
                    </footer>
                    
                   
                    
                </div>
		</article><!-- end of post new article -->
        
        
         <article class="module width_half">
			<header><h3>Spanish Copy</h3></header>
             
				<div class="module_content">
                	
                    <form action="index.php/englishready" method="post">
						<input type="hidden" name="sppr_id" id="sppr_id" value="<?php echo $sprow['sppr_id']?>" />
                        <input type="hidden" name="fpl_id" id="fpt_id" value="<?php echo $sprow['eng_id']?>" />
                        <fieldset>
							<label>Product Name</label>
                            
							<input type="text" name="pName" id="spName" required  value="<?php echo $sprow['prduct_name']?>">
						</fieldset>
						<fieldset>
                        	
							<label>Description</label><br />

							<textarea rows="12" name="pDesc" id="spDesc" required  style="width:490px;" class="jhtmls">
                            	<?php echo $sprow['product_description']?>
                            </textarea>
						</fieldset>
						<!--
                        <fieldset>
							<label>Product SKU</label>
							<input type="text" name="pSku" id="pSku" required readonly value="<?php echo $sprow['product_sku']?>">
						</fieldset>
                        <fieldset>
                        	
							<label>Product UPC</label>
							<input type="text" name="pupc" id="pupc" value="<?php echo $sprow['product_upc']?>">
						</fieldset>
                        <fieldset>
                       		<label>Product Cost</label>
							<input type="text" name="pCost" id="pCost" value="<?php echo $sprow['product_cost']?>">
						</fieldset>
                        <fieldset>
                        
							<label>Product Retail</label>
							<input type="text" name="pRetail" id="pRetail" value="<?php echo $sprow['product_retail']?>" >
						</fieldset>
                        <fieldset>
                        
							<label>Product MSRP</label>
							<input type="text" name="pmsrp" id="pmsrp" value="<?php echo $sprow['product_msrp']?>">
						</fieldset>
                        <fieldset>
                        
                        
							<label>Product MAP</label>
							<input type="text" name="pMAP" id="pMAP" value="<?php echo $sprow['product_map']?>">
						</fieldset>
                        <fieldset>
							<label>Product QTY</label>
							<input type="text" name="pQTY" id="pQTY" value="<?php echo $sprow['product_qty']?>">
						</fieldset>
                        <fieldset>
							<label>Product Inventory Level</label>
							<input type="text" name="pIlevel" id="pIlevel" value="<?php echo $sprow['product_inventory_level']?>">
						</fieldset>
                        <fieldset>
							<label>Product Brand</label>
							<input type="text" name="pBrand" id="pBrand" value="<?php echo $sprow['product_brand']?>">
						</fieldset>
                        -->
                        <fieldset>
                        
                        
							<label>Product Features</label>
							<textarea rows="12" name="pFeature" id="spFeature" style="width:490px;" class="jhtmls" ><?php echo htmlspecialchars_decode($sprow['product_features'])?></textarea>
						</fieldset>
                        
                        <fieldset>
                        
                            
                            
							<label>Product Specification</label>
							<textarea rows="12" name="pSpecs" id="spSpecs"  style="width:490px;" class="jhtmls"><?php echo htmlspecialchars_decode($sprow['product_specs'])?></textarea>
						</fieldset>
                        <footer>
                        <div class="submit_link">
                        
                        	<input type="button" value="Save" class="alt_btn" onclick="return savespanish()">
                        
                            <a id="spanish" href="index.php/preview?source=spenish&id=<?php echo $sprow['sppr_id']?>">
                           <input type="submit" value="Preview" class="alt_btn" onclick="return false"></a>
                           
                            <input type="submit" value="Review and Submit" class="alt_btn" onclick="return spanishready()">
              
                        </div>
                    </footer>
                    
                    </form>
                    
                    
                </div>
		</article><!-- end of post new article -->
       
        
		<div class="spacer"></div>
	</section>