       <table class="tablesorter1" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th><input type="checkbox" onclick="return selecteverything()" id="mainselect" /></th> 
    				<th>Product Name</th> 
    				<th>Product SKU</th> 
    				<th>Product UPC</th> 
    				<th>Brand</th> 
                    <th>Source</th>
                    <th>Status</th>
<!--                    <th>Action</th>-->
				</tr> 
			</thead> 
			<tbody> 
				

            <?php 
				
				$status = array(
								'1'=>'Archieve',
								'2'=>'Pending',
								'3'=>'Row',
								'4'=>'In Process',
								'5'=>'QA',
								'6'=>'Active'
								);				
				
				if(sizeof($content)>0){
				for($i=0;$i<sizeof($content);$i++){
				?>
				
                <tr> 
                <td><input type="checkbox" value="<?php echo $content[$i]->mpt_id?>" name="products[]" class="productcheck" /></td>
                <td><?php echo $content[$i]->prduct_name?></td> 
                <td><?php echo $content[$i]->product_sku?></td> 
                <td><?php echo $content[$i]->product_upc?></td> 
                <td><?php echo $content[$i]->product_brand?></td> 
               <td><?php echo $content[$i]->product_source?></td>  
               <td><?php echo $status[$content[$i]->status]?></td>  
              <!--  <td><input type="image" src="images/icn_edit.png" title="Edit"><input type="image" src="images/icn_trash.png" title="Trash"></td> -->
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
          