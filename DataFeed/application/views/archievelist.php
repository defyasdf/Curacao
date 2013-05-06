<section id="main" class="column">

<article class="module width_full">
		<header><h3 class="tabs_involved">Search Products</h3>
	
		</header>
			<div id="notify">
    	
    </div>       
        <div id="searchTop">
        	<button class="btn btn-warning cancel" type="reset" onclick="return resetAll()">
                                <i class="icon-ban-circle icon-white"></i>
                                <span>Reset Selected</span>
                            </button>
			
            <button class="btn btn-danger delete" type="button" onclick="return deleteAll()">
                                <i class="icon-trash icon-white"></i>
                                <span>Delete Completely</span>
            </button>
        </div>
        
		<div class="tab_container">
        
        <table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th><input type="checkbox" onclick="return selecteverything()" id="mainselect" /></th> 
    				<th>Product Name</th> 
    				<th>Product SKU</th> 
    				<th>Product UPC</th> 
    				<th>Brand</th> 
                    <th>Source</th>
                    <th>Status</th>
                    <th>Action</th>
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
                <td><input type="image" src="images/icn_edit.png" title="Edit"><input type="image" src="images/icn_trash.png" title="Trash"><a href="index.php/productwiz?sku=<?php echo $content[$i]->product_sku?>"><input type="image" src="images/icn_alert_success.png" title="Approve"></a></td> 
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