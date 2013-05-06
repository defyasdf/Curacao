<section id="main" class="column"> 

	<div id="notify">
    	
    </div>
<article class="module width_full">
			<header><h3>New KPI Detail</h3></header>
			
         <article class="module width_half">
            	 <div class="tab_container">
        
			        <table class="tablesorter" cellspacing="0"> 
                    	<thead>
                        	<tr>
                        		<th>Campaign</th>
                                <th>Visits</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php 
							
								$i = 1;
								foreach($content as $key=>$val){
								?>
								<tr>
                                	<td>
									<?php echo $key?>
                                   
                                    </td>
                                    <td><?php echo $val['count']?></td>
                                </tr>	
								<?php
								$i++;
								}
							?>
                        </tbody>
                    </table>
                  </div>
                  
            </article>
			 <article class="module width_half">
             	<div id="mainchart" style="width: 600px; height: 400px;"></div>
             </article>
		</article><!-- end of post new article -->
      
 </section>
 
	
 <script type="text/javascript">
 	function closepopup(){
		tb_remove();	
		return false;
	}
 </script>   