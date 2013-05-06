<section id="main" class="column"> 

	<article class="module width_full">
		<header><h3 class="tabs_involved">iCuracao Users</h3>
	
		</header>

		<div class="tab_container">
<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th></th> 
    				<th>User</th> 
    				<th>Activity</th> 
    				<th>Date</th> 
    				<th>Time</th> 
  			</tr> 
			</thead> 
			<tbody> 
<?php 		

		
	if(sizeof($content)>0){
		for($i=0;$i<sizeof($content['time']);$i++){
?>				
            
            <tr> 
                <td></td>
                <td><?php echo $content['user'][$i]?></td> 
                <td><?php echo $content['activity'][$i]?></td> 
                <td><?php echo $content['date'][$i]?></td> 
                <td><?php echo $content['time'][$i]?></td> 
        
        
            </tr> 
				
             <?php }
			 
		}else{
			
			?>
				<tr>
                	<td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                    <td>No Data</td>
                </tr>
			<?php	
			}
			?>
			</tbody> 
			</table>
            </div></article>
 </section>