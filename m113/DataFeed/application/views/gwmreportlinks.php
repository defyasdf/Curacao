<section id="main" class="column"> 

	<div id="notify">
		
    </div>
<article class="module width_full">
			<header><h3>Curacao Report</h3></header>
				
                <div id="notify"></div>
            
            	<div class="module_content">
                	
                    <div class="tab_container">
        					         <table class="tablesorter" cellspacing="0"> 
                                                <thead>
                                                    <tr>
                                                        <th>Waterfall</th>
                                                        <th>values</th>
                                                        <th>Percentile</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                 <tr>
                                                    <td colspan="3">
                                                       <hr>
                                                    </td>
                                                </tr>	
                                                	<td>
                                                         Total Clicks:

                                                    </td>
                                                    <td>
                                                        <?php echo $content['count']?>
                                                    </td>
                                                    <td>
                                                    	100%
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>
                                                        Total Add to Cart:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['addtocart']?>
                                                    </td>
                                                    <td>
                                                        <?php 
															$add = number_format((float)$content['addtocart']/(float)$content['count'],2);
															echo (float)$add*100;
															echo '%';
														?>
                                                    </td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Total Number of Checkout page:

                                                    </td>
                                                    <td>
                                                        <?php echo $content['checkout']?>
                                                    </td>
                                                    
                                                     <td>
                                                        <?php 
															$add = number_format((float)$content['checkout']/(float)$content['addtocart'],2);
															echo (float)$add*100;
															echo '%';
														?>
                                                    </td>
                                                    
                                                </tr>
                                               
                                                <tr>
                                                    <td>
                                                        Completed Purchase:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['completed']?>
                                                    </td>
                                                    <td>
                                                        <?php 
															$add = number_format((float)$content['completed']/(float)$content['checkout'],2);
															echo (float)$add*100;
															echo '%';
														?>
                                                    </td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                       <hr>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                      <a href="#" onclick="return breakdown()">Show Breakdown</a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                       <hr>
                                                    </td>
                                                </tr>
                                                </tbody>
                                           </table>
                                           
                                           
                                                
                                               
                                    
                  </div>       
                
				</div>
			
		</article><!-- end of post new article -->
       <article class="module width_full" id="breakdownContainer" style="display:none;">
			<header><h3>GWM Report Break down</h3></header>
       		<div class="module_content" id="breakdown">
            	<img src="/images/loadingAnimation.gif">
            </div>
       </article>
       <article class="module width_full">
			<header><h3>GWM Break down</h3></header>
       		<div class="module_content">
                    <div class="tab_container">
						<?php 
							$aff = $content['aff'];
							foreach($aff as $key=>$val){
								?>
								<div style="clear:both">
                                	<div class="affiliateIds" id="<?php echo $val['subid']?>title" style="float:left; width:25%;" onClick="return showaffiliate('<?php echo $val['subid']?>')">
                                    	<h4><?php echo $val['subid']?></h4>
                                    </div>
                                    <div id="<?php echo $val['subid']?>loading" class="affloading" style="float:left; width:25%; display:none;">
                                    	<img src="/images/loadingAnimation.gif">
                                    </div>
                                	<div id="result<?php echo $val['subid']?>" class="aff_result" style="display:none">
                                	</div>
                               </div>
						<?php 
                        	}
						?>
                    </div>
           </div>
       
       </article> 

 </section>
 
 <script type="text/javascript">
 	function showaffiliate(aff){
		jQuery("#"+aff+"loading").show();
		var login_url = 'index.php/gwmreportaff'
		jQuery.ajax({ 
				type: 'post',
				data: {'aff':aff},
				url:  login_url,                    
				success: function(data) {
					//alert(data)
					jQuery(".affloading").hide();
					jQuery(".aff_result").hide();
					jQuery("#result"+aff).html(data);
					jQuery("#result"+aff).show('slow')
					
				}.bind(this)
		 });
		 
		 return false;
	}
	
	function breakdown(){
		$("#breakdownContainer").show('slow');
		var login_url = 'index.php/gwmbreakdown'
		jQuery.ajax({ 
				type: 'post',
				data: {'sdate':'<?php echo $_REQUEST['sdate']?>','edate':'<?php echo $_REQUEST['edate']?>'},
				url:  login_url,                    
				success: function(data) {
					jQuery("#breakdown").html(data);
				}.bind(this)
		 });
		
		return false;
	}
 </script>