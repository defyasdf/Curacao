<section id="main" class="column">
<article class="module width_full">
		<header><h3 class="tabs_involved">In Process Products</h3>
	
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
                <td><input type="image" src="images/icn_edit.png" title="Edit"><input type="image" src="images/icn_trash.png" title="Trash"><a href="index.php/productwiz?sku=<?php echo $content[$i]->product_upc?>"><input type="image" src="images/icn_alert_success.png" title="Approve"></a></td> 
            </tr> 
                				
				<?php
				}
				}else{
			
			?>
				<tr>
                	<td>No Data</td>
                    <td>No Data</td>
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
            </div>
</article>
</section>