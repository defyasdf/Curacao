<section id="main" class="column"> 

	<div id="notify">
		
    </div>
<article class="module width_full">
			<header><h3>GWM Report for <?php echo $_REQUEST['aff'];?></h3></header>
				
                <div id="notify"></div>
            
            	<div class="module_content">
                	
                    <div class="tab_container">
        					         <table class="tablesorter" cellspacing="0"> 
                                                <thead>
                                                    <tr>
                                                        <th>Waterfall</th>
                                                        <th>values</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                 <tr>
                                                    <td colspan="2">
                                                       <hr>
                                                    </td>
                                                </tr>	
                                                	<td>
                                                         Total Clicks:

                                                    </td>
                                                    <td>
                                                        <?php echo $content['count']?>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>
                                                        Total Add to Cart:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['addtocart']?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Total Number of Checkout page:

                                                    </td>
                                                    <td>
                                                        <?php echo $content['checkout']?>
                                                    </td>
                                                </tr>
                                               
                                                <tr>
                                                    <td>
                                                        Completed Purchase:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['completed']?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                       <hr>
                                                    </td>
                                                </tr>
                                                </tbody>
                                           </table>
                                           
                                           
                                                
                                               
                                    
                  </div>       
                
				</div>
			
		</article><!-- end of post new article -->
     
 </section>
 
 