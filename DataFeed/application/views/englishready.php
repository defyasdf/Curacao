<section id="main" class="column">
        <article class="module width_full">
			<header><h3>English Ready</h3></header>
             <div id="notify"><?php echo $msg;?></div>
				<div class="module_content">
                    <article class="module width_full">
		<header><h3 class="tabs_involved">Pening Translation and review</h3>
	
		</header>
      <div class="tab_container">
<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th></th> 
    				<th>Product Name</th> 
    				<th>Product SKU</th> 
    				<th>Product UPC</th> 
    				<th>Brand</th> 
                    <th>Source</th>
                    <th>Action</th>
				</tr> 
			</thead> 
			<tbody> 
 <?php 
				
				
				if(sizeof($content)>0){
				for($i=0;$i<sizeof($content);$i++){
				?>
				
                <tr> 
                <td></td>
                <td><?php echo $content[$i]->prduct_name?></td> 
                <td><?php echo $content[$i]->product_sku?></td> 
                <td><?php echo $content[$i]->product_upc?></td> 
                <td><?php echo $content[$i]->product_brand?></td> 
               <td><?php echo $content[$i]->product_source?></td>  
                <td><input type="image" src="images/icn_edit.png" title="Edit"><input type="image" src="images/icn_trash.png" title="Trash"><a href="index.php/review?sku=<?php echo $content[$i]->product_sku?>"><input type="image" src="images/icn_alert_success.png" title="Approve"></a></td> 
            </tr> 
                				
				<?php
				}
				}else{
			
			?>
				<tr>
                	<td colspan="7">No Product is Panding</td>
                </tr>
			<?php	
			}
			?>
			</tbody>
            
            </table>
            </div></article>
                    
                    
				</div>
			
		</article><!-- end of post new article -->
        
		<div class="spacer"></div>
	</section>

