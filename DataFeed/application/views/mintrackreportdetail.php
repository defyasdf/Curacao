<section id="main" class="column"> 

	<div id="notify">
    	
    </div>
   
<article class="module width_full">
			<header><h3><?php echo $_GET['campaign'].' KPI Detail'; 
				if(isset($_GET['keyword'])){
					echo ' For keyword "'.str_replace('plssgn','+',$_GET['keyword']).'"';
				} 
			?>  </h3></header>
			
         <article class="module width_full">
            	 <div class="tab_container">
        			<?php foreach($content as $key=>$val){?>
			         <table class="tablesorter" cellspacing="0"> 
                                                <thead>
                                                    <tr>
                                                        <th>Waterfall</th>
                                                        <th>values</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                	<td>
                                                        Clicks:
                                                    </td>
                                                    <td>
                                                        <?php echo $val['count']?>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>
                                                        First Page:
                                                    </td>
                                                    <td>
                                                        <?php echo $val['firstpage']?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Complete First Page of Credit applicaiton:
                                                    </td>
                                                    <td>
                                                        <?php echo $val['completefirstpage']?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Authentication Pass:
                                                    </td>
                                                    <td>
                                                        <?php echo $val['authpass']?>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>
                                                        Submit Application:
                                                    </td>
                                                    <td>
                                                        <?php echo $val['submitapp']?>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>
                                                        Approved:
                                                    </td>
                                                    <td>
                                                        <?php echo $val['approve']?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Pending:
                                                    </td>
                                                    <td>
                                                        <?php echo $val['pending']?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Decline:
                                                    </td>
                                                    <td>
                                                        <?php echo $val['decline']?>
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
                                                        <?php echo $val['shoppingcart']?>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                        Checkout:
                                                    </td>
                                                    <td>
                                                        <?php echo $val['checkout']?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Cart Amount:
                                                    </td>
                                                    <td>
                                                        <?php echo $val['cartamount']?>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td>
                                                        Finish checkout:
                                                    </td>
                                                    <td>
                                                        <?php echo $val['finishcheckout']?>
                                                    </td>
                                                </tr>
                                                
                                                <tr>
                                                    <td>
                                                        Total Purchase:
                                                    </td>
                                                    <td>
                                                        <?php echo $val['totalamount']?>
                                                    </td>
                                                </tr>
                                                <?php if(!isset($_GET['keyword'])){?>
                                                  <tr>
                                                    <td colspan="2">
                                                       <hr>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td colspan="2">
                                                       <strong> Keywords</strong>
                                                    </td>
                                                </tr>
                                                 <tr>
                                                    <td colspan="2">
                                                       <hr>
                                                    </td>
                                                </tr>
                                                <?php 
												
												foreach($val['keyword'] as $k=>$v){?>
                                                <tr>
                                                	<td>
                                                    	<a href="index.php/mintrackdetail/?campaign=<?php echo $_GET['campaign']?>&sdate=<?php echo $_GET['sdate']?>&edate=<?php echo $_GET['edate']?>&keyword=<?php echo str_replace('+','plssgn',$k)?>"><?php echo $k?></a>
                                                    </td>
                                                    <td><?php echo $v['count']?></td>
                                                </tr>
                                                <?php }
												}
												?>
                                                </tbody>
                                           </table>
                                       <?php }?>
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
                    <div id="chart_div2" style="width: 600px; height: 400px;"></div>
                </article>
                
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