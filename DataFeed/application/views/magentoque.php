<div id="ajaxcontentbg">
	
</div>

<div id="prioritydiv">

   	<form>
    <fieldset>
    <label>Write a Comment</label>
<textarea name="comment" id="comment"></textarea>


  </form>
  
  <br />
  
<br /><br />
<br />


         <button onclick="return unapprove()" class="btn btn btn-primary" style="margin-left:15px; margin-bottom:10px;">
                    <i class="icon-trash icon-white"></i>
                    <span>Process Content</span>
                </button>
        </fieldset><br />
<br />
<input type="hidden" id="prsku" name="prsku" />
<input type="hidden" id="prid" name="prid" />
    </div>

<section id="main" class="column"> 
   
      <article class="module width_full">
         
		<header><h3 class="tabs_involved">Approved Products</h3>
	
		</header>
        
        <!--	<div id="notify">
    	
    </div>
        <div id="searchTop">
        	 <button class="btn btn-success" type="button" onclick="return addtomagentoque()">
                                <i class="icon-ban-circle icon-white"></i>
                                <span>Add to Magento</span>
           </button>	
        </div>-->
        
      <div class="tab_container">
<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th><input type="checkbox" onclick="return selecteverything()" id="mainselect" /></th> 
    				<th>Product Name</th> 
    				<th>Product SKU</th> 
    				<th>Product UPC</th> 
    				<th>Brand</th> 
                    <th>Status</th>
                  <!--  <th>Action</th>-->
				</tr> 
			</thead> 
			<tbody> 
 <?php 
				
				$status = array(
								'0'=>'Pending',
								'1'=>'Added to Magento'
								);	
				
				
				if(sizeof($content)>0){
				for($i=0;$i<sizeof($content);$i++){
				?>
				
                <tr <?php if($content[$i]->magento_product_id>0){?> bgcolor="#E2F6C5"<?php }?>> 
                <td><input type="checkbox" value="<?php echo $content[$i]->fpl_id?>" name="products[]" class="productcheck" /></td>
                <td><?php echo $content[$i]->prduct_name?></td> 
                <td><?php echo $content[$i]->product_sku?></td> 
                <td><?php echo $content[$i]->product_upc?></td> 
                <td><?php echo $content[$i]->product_brand?></td> 
               <td><?php echo $status[$content[$i]->mstatus]?></td>  
                <!--<td><input type="image" src="images/icn_edit.png" title="Edit"><input type="image" src="images/icn_trash.png" title="Trash"><?php if($content[$i]->magento_product_id==0){?><a href="" onclick="return addtoMagento('<?php echo $content[$i]->fpl_id?>')"><input type="image" src="images/icn_alert_success.png" title="Approve"></a><?php }?>
                <a href="" onclick="return setunapprove('<?php echo $content[$i]->fpl_id?>','<?php echo $content[$i]->product_sku?>')"><input type="image" src="images/icn_alert_error.png" title="Unapprove"></a>
                
                </td>--> 
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
                 <!--    <td>No Data</td>-->
                </tr>
			<?php	
			}
			?>
			</tbody>
            
            </table>
            </div></article>
                    
     
		<div class="spacer"></div>
	</section>

