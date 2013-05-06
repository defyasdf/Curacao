<section id="main" class="column">
		
		
        <article class="module width_full">
			<header><h3>Product Wizard</h3></header>
             
				<div class="module_content">
                  	
                        
                        <form action="index.php/finalproduct" method="post">
                        <input type="hidden" name="sku" value="<?php echo $content['psku'][0]?>">
                        <input type="hidden" name="brand" value="<?php echo $content['brand'][0]?>" />
                        <input type="hidden" value="<?php echo $content['pupc'][0]?>" name="finalupc" id="upc" />
                        <header><h3 style="margin-left:10px;">Product Title</h3></header>                        
                        <div id="step1" class="steps">
                        
                        <?php $num = sizeof($content['pname']);
					//		echo $num;

//							print_r($content);
							
						?>
                       	<div class="tab_container">
                            <table class="tablesorter" cellspacing="0"> 
                                    <thead> 
                                        <tr> 
                                            <th>Sources:</th> 
                                            <?php for($i=0;$i<$num;$i++){?>
                                            <th><?php echo $content['psource'][$i]?></th>
                                            <?php }?>
                                            <th>Etilize</th>
                                            <th>Final Copy</th>
                                            
                                            
                                            </tr>
                                            </thead>
                                       <tbody> 
                                      

                        
										<tr>
                                        <td>Product Name</td>
										<?php for($i=0;$i<$num;$i++){?>
										<td valign="top"><input type="radio" required name="pname" id="pnm<?php echo $i?>" onclick="return placevalue('pnm<?php echo $i?>','pname')" value="<?php echo $content['pname'][$i]?>"> <?php echo $content['pname'][$i]?></td>         <?php }?>                              	
                                        <td valign="top"><input type="radio" required name="pname" id="pnme" onclick="return placevalue('pnme','pname')" value="<?php echo htmlspecialchars($content['etilizeName'],ENT_QUOTES)?>"> <?php echo $content['etilizeName']?></td>                                       	 
                                        <td valign="top"><input type="text" name="finalpname" id="pname" /> </td>                                       	                                         
                                         </tr>
                                         
                                       <!-- <tr>
                                        <td>Product UPC</td>
										
										<td valign="top"><input type="radio" id="up" onclick="return placevalue('up','upc')" required name="pupc" value="<?php echo $content['pupc'][0]?>"> <?php echo $content['pupc'][0]?></td>                                       	
										<td valign="top"><input type="radio" id="up1" onclick="return placevalue('up1','upc')" required name="pupc" value="<?php echo $content['pupc'][0]?>"> <?php echo $content['pupc'][0]?></td>
                                        
										<td valign="top"> </td>                                       	                                         
                                         
                                         </tr>-->
                                         
                                    
                                         
                                       </tbody>
                                       
                                       </table>
                   
                   		<div id="backforth">
                        	<div id="back">
                            	<button class="btn btn-success start" type="submit" onclick="return revertproduct('<?php echo $_GET['sku']?>')">
                                    <i class="icon-upload icon-white"></i>
                                    <span>Revert Back</span>
                                </button>
                            </div>
                            <div id="forward">
                                <img src="images/next.jpg" onClick="return nextStep('step2')"/>                            
                            </div>
                        </div>
                        
                        <div class="clear"></div>
                        
                    </div>
                    </div>
                                       
                    <header><h3 style="margin-left:10px;">Product Description</h3></header>
                    <div id="step2" class="steps">
                     			 	<div class="tab_container">
                            <table class="tablesorter" cellspacing="0"> 
                                    <thead> 
                                        <tr> 
                                            <th width="10%">Sources:</th> 
                                           <?php for($i=0;$i<$num;$i++){?>
                                            <th width="25%"><?php echo $content['psource'][$i]?></th>
                                            <?php }?>
                                            <th width="25%">Etilize</th>
                                            <th width="30%">Final Copy</th>
                                           
                                        </tr>
                                   </thead>
                                   <tbody> 
                                       		 
                                         <tr>
                                        <td valign="top">Product Description</td>
										<?php for($i=0;$i<$num;$i++){?>
										<td valign="top"><input type="radio" required name="pdesc"  onclick="return placehtml('dsc<?php echo $i?>','pdesc')"><div id="dsc<?php echo $i?>"><?php echo htmlspecialchars($content['pdesc'][$i],ENT_QUOTES)?></div></td>              <?php }?>                         	
										<td valign="top"><input type="radio" required name="pdesc" onclick="return placehtml('dsce','pdesc')"><div id="dsce"> <?php echo ($content['etilizeDesc'])?></div></td>											
										<td valign="top"><textarea name="finalpdesc" id="pdesc"></textarea></td>
                                         
                                         </tr>
                                          
                                      </tbody>
                           </table>
                           
                           <div id="backforth">
                        	<div id="back">
                            	<img src="images/previous.jpg" onClick="return nextStep('step1')" />
                            </div>
                            <div id="forward">
                                <img src="images/next.jpg" onClick="return nextStep('step3')"/>                            
                            </div>
                        </div>
                        
                        <div class="clear"></div>
                           
                    </div>
                    
                    </div>
                    <header><h3 style="margin-left:10px;">Product Features</h3></header>
                     <div id="step3" class="steps">
                     			 	<div class="tab_container">
                            <table class="tablesorter" cellspacing="0"> 
                                    <thead> 
                                        <tr> 
                                            <th>Sources:</th> 
                                          <?php for($i=0;$i<$num;$i++){?>
                                            <th><?php echo $content['psource'][$i]?></th>
                                            <?php }?>
                                            <th>Etilize</th>
                                            <th>Final Copy</th>
                                            </tr>
                                            </thead>
                                       <tbody> 
                                       		 
                                         <tr>
                                        <td valign="top">Product Features</td>
										 <?php for($i=0;$i<$num;$i++){?>
										<td valign="top"><input type="radio" required name="pfeature" value="<?php echo $content['pfeature'][0]?>"  onclick="return placehtml('ftr<?php echo $i?>','features')"> <div id="ftr<?php echo $i?>"><?php echo htmlspecialchars_decode($content['pfeature'][$i],ENT_QUOTES)?></div></td>                                     
                                        <?php }?>
                                        <td valign="top"><input type="radio" required name="pfeature"  onclick="return placehtml('ftre','features')" value="<?php echo $content['pfeature'][0]?>"> <div id="ftre"><?php echo ($content['etilizeFeature'])?></div></td>                                       	
											
										<td valign="top"><textarea name="finalfeatures" id="features"></textarea></td>
                                         
                                         </tr>
                                           
                                      </tbody>
                           </table>
                           
                           <div id="backforth">
                        	<div id="back">
                            	<img src="images/previous.jpg" onClick="return nextStep('step2')" />
                            </div>
                            <div id="forward">
                                <img src="images/next.jpg" onClick="return nextStep('step4')"/>                            
                            </div>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                    
                    </div>
                    <header><h3 style="margin-left:10px;">Product Specifications</h3></header>
                           <div id="step4" class="steps">
                           <div>
                           		<a href="index.php/maketable" id="various3" target="_blank"><button  class="btn btn btn-primary" style="margin-left:15px; margin-bottom:10px;">
                                    <i class="icon-trash icon-white"></i>
                                    <span>Generate</span>
                                </button></a>
                           </div>
                     			 	<div class="tab_container">
                            <table class="tablesorter" cellspacing="0"> 
                                    <thead> 
                                        <tr> 
                                            <th>Sources:</th> 
                                           <?php for($i=0;$i<$num;$i++){?>
                                            <th><?php echo $content['psource'][$i]?></th>
                                            <?php }?>
                                            <th>Etilize</th>
                                            <th>Final Copy</th>
                                            </tr>
                                            </thead>
                                       <tbody> 
                                       		 
                                         <tr>
                                        <td valign="top">Product Specifications</td>
										 <?php for($i=0;$i<$num;$i++){?>
										<td valign="top"><input type="radio" required name="pspecs" onclick="return placehtml('spc<?php echo $i?>','specs')" value="<?php echo $content['pspecs'][0]?>"><div id="spc<?php echo $i?>">  <?php echo htmlspecialchars_decode($content['pspecs'][$i])?></div></td>   
                                        <?php }?>                                    	
										<td valign="top"><input type="radio" required name="pspecs" onclick="return placehtml('spce','specs')" value="<?php echo $content['pspecs'][0]?>"> <div id="spce">
                                       <table>
                                       	<?php
											$new = $content['etilizeSpecs'];
											
											
										foreach($new as $k=>$v){
						
													echo '<tr>';
														echo '<td>';
														echo $k;
														echo '</td>';
														echo '<td>';
														echo $v;
														echo '</td>';
													echo '</tr>';
												
												}
											?> 
                                       </table> 
                                       </div></td>                                       		
										<td valign="top"><textarea name="finalspecs" id="specs"></textarea></td>
                                         
                                         </tr>
                                           
                                      </tbody>
                           </table>
                           
                           <div id="backforth">
                        	<div id="back">
                            	<img src="images/previous.jpg" onClick="return nextStep('step3')" />
                            </div>
                            <div id="forward">
                                <img src="images/next.jpg" onClick="return nextStep('step5')"/>                            
                            </div>
                        </div>
                        
                        <div class="clear"></div>
                           
                           
                    </div>
                    </div>
                   <header><h3 style="margin-left:10px;">Images</h3></header>  
                   <div id="step5" class="steps">
                    
                    
                    	<table class="tablesorter" cellspacing="0" style="margin:0;"> 
                        <tbody>
                        <tr>
                                        <td valign="top">Product Image</td>
										<?php 
											$img = explode(',',$content['pimg'][0]);
											
											for($i=0;$i<sizeof($img);$i++){
												$pimg = getimagesize($img[$i]);
											?>
                                            
                                            <td valign="top"><input type="checkbox" name="pimg[]" value="<?php echo $img[$i]?>">
                                        
		                                        <img src="<?php echo $img[$i]?>" width="60" height="60">
                                                <br />
												Width : <?php echo $pimg[0]?><br />
												Height :<?php echo $pimg[1]?>
                                         </td>  
                                            
											<?php
											}
											
										?>
										                                     	
										<?php 
											//$img = explode(',',$content['etilizeimg'][0]);
											
											for($j=0;$j<sizeof($content['etilizeimg']);$j++){
											?>
                                            
                                            <td valign="top"><input type="checkbox" name="pimg[]" value="<?php echo $content['etilizeimg'][$j]?>">
                                        
		                                        <img src="<?php echo $content['etilizeimg'][$j]?>" width="60" height="60">
                                         </td>  
                                            
											<?php
											}
											
										?>	
                                                                         	
                                         
                                    </tr>
                        </tbody>
                        </table>
                        
                    	<div id="backforth">
                        	<div id="back">
                            	<img src="images/previous.jpg" onClick="return nextStep('step4')" />
                            </div>
                            <div id="forward">
                               <input type="submit" class="alt_btn" value="Submit">                          
                            </div>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                    
                    
		       </form>         
			
		</article><!-- end of post new article -->
        
		<div class="spacer"></div>
	</section>