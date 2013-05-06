<section id="main" class="column"> 

	<div id="notify">
    	
    </div>
   
<article class="module width_full">
			<header><h3>Pre-approved KPI</h3></header>
			
         <article class="module width_full">
            	 <div class="tab_container">
        					         <table class="tablesorter" cellspacing="0"> 
                                                <thead>
                                                    <tr>
                                                        <th>Waterfall</th>
                                                        <th>values</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                	<td>
                                                        Entered Pre-Approved Code:

                                                    </td>
                                                    <td>
                                                        <?php echo $content['count']?>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>
                                                        Completed First Page Application:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['step1']?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Completed Second Page Application:

                                                    </td>
                                                    <td>
                                                        <?php echo $content['step2']?>
                                                    </td>
                                                </tr>
                                               
                                                <tr>
                                                    <td>
                                                        Approved:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['approve']?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                       Approved Average Line:

                                                    </td>
                                                    <td>
                                                        <?php echo round($content['approvecreditline'],2)?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Pending:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['pending']?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Decline:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['decline']?>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td colspan="2">
                                                       <hr>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td colspan="2">
                                                       <strong> Shopping cart Data</strong>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td colspan="2">
                                                       <hr>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                        Added to Cart:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['shoppingcart']?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Cart Amount:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['cartamount']?>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                        Went to Checkout:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['checkout']?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Went to Checkout Amount:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['checkoutamount']?>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                        Completed Checkout:

                                                    </td>
                                                    <td>
                                                        <?php echo $content['finishcheckout']?>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>
                                                        Completed Checkout Amount:
                                                    </td>
                                                    <td>
                                                        <?php echo $content['totalamount']?>
                                                    </td>
                                                </tr>
                                                
                                                </tbody>
                                           </table>
                                    
                  </div>
                  
            </article>
			
		</article><!-- end of post new article -->
        
         <article class="module width_full">   
             <article class="module width_half">
                   
                     <div id="chart_div" style="width: 600px; height: 400px;"></div>
                </article>	
                
                <article class="module width_half">
                   
                     <div id="chart_div1" style="width: 600px; height: 400px;"></div>
                     
                </article>	
              </article>
              <article class="module width_full">  
               
                
                
                <article class="module width_half">
                    <div id="chart_div3" style="width: 600px; height: 400px;"></div>
                </article>    
            </article>
      
 </section>
 
	
 <script type="text/javascript">
 	function closepopup(){
		tb_remove();	
		return false;
	}
 </script>   